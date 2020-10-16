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

  public function generateQrCodes($qr_text) {
      // The below code will automatically create the path for the img.
      $path = '';
      $directory = "public://Images/QrCodes/";
      file_prepare_directory($directory, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY);
      // Name of the generated image.
      $qrName = 'myQrcode'.
      $uri = $directory . $qrName . '.png'; // Generates a png image.
      $path = drupal_realpath($uri);
      // Generate QR code image.
      \PHPQRCode\QRcode::png($qr_text, $path, 'L', 4, 2);
      return $path;
    }


}

