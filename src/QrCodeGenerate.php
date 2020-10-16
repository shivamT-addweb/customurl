<?php

/**
 * @file
 * Contains \Drupal\customurl\QrCodeGenerate.
 */

namespace Drupal\customurl;

use Drupal\taxonomy\Entity\Term;
use Drupal\node\Entity\Node;
use \Drupal\file\Entity\File;

/**
 * Defines QrCodeGenerate service.
 */
class QrCodeGenerate {
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

