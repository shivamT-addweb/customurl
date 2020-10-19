<?php

namespace Drupal\customurl\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use \Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Defines ViewURLpageController class.
 */
class ViewURLpageController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content($id = NULL) {
    $connection = \Drupal::database();
    $query      = db_select('customurls', 'curl');
    $query->condition('curl.newurl', $id)->fields('curl');
    $result  = $query->execute();
    $results = $result->fetchAssoc(\PDO::FETCH_OBJ);

    $oldurl = $results['oldurl'];
    $newurl = $results['newurl'];
    $urlid =  $results['id'];

    $json_url = urlencode($oldurl);  

    $page_content = 'This page provides the short url link details of actual url. <br/>';
    $page_content = $page_content .'Actual url link is '.$oldurl.' <br/>';
    $page_content = $page_content .'New url link is <div id ="newlink" class="'.$urlid.'"><a href="'.$oldurl.'"> '.$newurl.' </a></div> <br/>';
    $page_content = $page_content .'Url in JSON form is '.$json_url.' <br/>';
    return [
      '#type' => 'markup',
      '#markup' => $this->t($page_content),
    ];
  }

}

