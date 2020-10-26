<?php

namespace Drupal\customurl\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;

/**
 * Defines URLListController class.
 */
class URLListController extends ControllerBase {

  /**
   * @var \Drupal\Core\Database\Connection
   */
  private $injected_database;

  public function __construct(Connection $injected_database) {
    $this->injected_database = $injected_database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  public function myPage() {
    return [
     '#markup' => time(),
     '#cache' => ['max-age' => 0,],    //Set cache for 0 seconds.
    ];
  }


  public function content() {
    $header = array(
        array('data' => t('Id'), 'field' => 'id'),
        array('data' => t('Old URL'), 'field' => 'oldurl'),
        array('data' => t('New URL'), 'field' => 'newurl'),
        array('data' => t('Counter'), 'field' => 'counter'),
        array('data' => t('View')),
      );

      $query = $this->injected_database->select('customurls', 'curl');
      $query->fields('curl');
      $table_sort = $query->extend('Drupal\Core\Database\Query\TableSortExtender')->orderByHeader($header, 'DESC');
      $pager = $table_sort->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10);
      $result = $pager->execute();

      foreach($result as $row) {
        // $account = \Drupal\user\Entity\User::load($row->uid); // pass user uid
        // $user = $account->getUsername();
        $view_page  = Url::fromUserInput('/view/'.$row->newurl);
          $rows[] = array(
                'id' =>$row->id,
                'oldurl' => $row->oldurl,
                'newurl' => $row->newurl,
                'counter' => $row->counter,
                \Drupal::l('View', $view_page),
            );

      }


      $build = array(
          '#markup' => t('List of all custom short urls')
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
