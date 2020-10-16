<?php

namespace Drupal\customurl\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a "Custom url form block".
 *
 * @Block(
 *   id = "custom_url_form_block",
 *   admin_label = @Translation("Custom URL form block")
 * )
 */
class CustomURLBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\customurl\Form\URLForm');
    return $form;
  }

}
