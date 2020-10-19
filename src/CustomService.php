<?php

/**
 * @file
 * Contains \Drupal\customurl\CustomService.
 */

namespace Drupal\customurl;

use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;

/**
 * Defines Custom service.
 */
class CustomService {
  // Update counter after click on link
  public function updateounter($id = ''){
        $connection = \Drupal::database();
        $query      = db_select('customurls', 'curl');
        $query->condition('curl.id', $id)->fields('curl');
        $result  = $query->execute();
        $results = $result->fetchAssoc(\PDO::FETCH_OBJ);
        
        $counter = $results['counter'] + 1;
        //Update url Table
        \Drupal::database()->update('customurls')->fields(array(
            'counter' => $counter
        ))->condition('id', $id)->execute();
        $message = "Counter updated.";

        return $message;
  }

}

