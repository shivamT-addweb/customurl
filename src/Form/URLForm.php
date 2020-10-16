<?php

namespace Drupal\customurl\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Url;

/**
 * Implementing a form.
 */
class URLForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_submit_demo';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('URL'),
      '#required' => TRUE
    ];

    $form['actions'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];


    return $form;
  }


  /**
   * Submitting the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
        $connection = \Drupal::database();
        $query      = db_select('customurls', 'curl');
        $query->condition('curl.oldurl', $form_state->getValue('url'))->fields('curl');
        $result  = $query->execute();
        $results = $result->fetchAssoc(\PDO::FETCH_OBJ);

        if ($results['oldurl'] != ""){
          $url = Url::fromUserInput('/view/'.$results['newurl']);
          $form_state->setRedirectUrl($url);
          drupal_set_message('Short url already exists for this');
        }else {
          $chars = "abcdefghijklmnopqrstuvwxyz";
          $chars_length = 3;
          $nums = "0123456789";
          $num_length = 3;
          
            // create the charset for the codes
            $charset = str_shuffle($chars);
            $char_code = substr($charset, 0, $chars_length);

            // create the number set for the codes
            $numset = str_shuffle($nums);
            $num_code = substr($numset, 0, $num_length);

            $newurl = $char_code.$num_code.".addweb";
            \Drupal::database()->insert('customurls')->fields(array(
                      'oldurl' => $form_state->getValue('url'),
                      'newurl' => $newurl,
                  ))->execute();
            $url = Url::fromUserInput('/view/'.$newurl);
          $form_state->setRedirectUrl($url);
          drupal_set_message('Url created successfully.');
        }
    
  }

}
