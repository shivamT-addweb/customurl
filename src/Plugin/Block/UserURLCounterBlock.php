<?php

namespace Drupal\customurl\PLugin\Block;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Connection;

/**
 * QR image block.
 *
 * @Block(
 *   id = "user_url_counter_block",
 *   admin_label = @Translation("Custom URL User Counter Block"),
 *   category = @Translation("Create Custom URL")
 * )
 */
class UserURLCounterBlock extends BlockBase{

  protected $custom_service;
  /**
   * Plugin manager.
   *
   * @var \Drupal\Component\Plugin\PluginManagerInterface
   */
  protected $pluginManager;

  /**
   * {@inheritdoc}
   */
  public function build() {
    $this->custom_service = \Drupal::service('customurl.counter_service');
    $current_url = Url::fromRoute('<current>');
    $path = $current_url->toString();
    $path_array = explode('/', $path);
    $newurl = end($path_array);

    $data = $this->custom_service-> selectUrl($newurl);
    $id = $data['id'];
    $oldurl = $data['oldurl'];
    $newurl = $data['newurl'];
    $url_id = $data['id'];

    $header = array(
        array('data' => t('Id'), 'field' => 'id'),
        array('data' => t('User Id'), 'field' => 'user_id'),
        array('data' => t('Old URL')),
        array('data' => t('New URL')),
        array('data' => t('User Counter'), 'field' => 'user_counter'),
      );

      $query = \Drupal::database()->select('customurl_user_counter', 'cuc');
      $query->condition('cuc.url_id', $id);
      $query->fields('cuc');
      $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')->orderByHeader($header, 'DESC');
      $pager = $table_sort->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10);
      $result = $pager->execute();

      foreach($result as $row) {
          $rows[] = array(
                'id' =>$row->id,
                'user_id' =>$row->user_id,
                'oldurl' => $oldurl,
                'newurl' => $newurl,
                'user_counter' => $row->user_counter,
            );

      }


      $build = array(
          '#markup' => t('URL details'),
          '#cache' => ['max-age' => 0,],   //Set cache for 0 seconds.
      );

      $build['url_table'] = array(
        '#theme' => 'table', '#header' => $header,
          '#rows' => $rows
      );
     $build['pager'] = array(
       '#type' => 'pager'
     );

    return $build;
  }

}
