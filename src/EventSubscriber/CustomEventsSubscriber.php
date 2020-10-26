<?php
  
namespace Drupal\customurl\EventSubscriber;
  
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\customurl\CustomEvent;
use Drupal\user\Entity\User;
// use Drupal\Core\Config\ConfigCrudEvent;
// use Drupal\Core\Config\ConfigEvents;
  
class CustomEventsSubscriber implements EventSubscriberInterface {
  

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    //$events[ConfigEvents::SAVE][] = array('onSavingConfig', 800);
    $events[CustomEvent::COUNTER][] = array('updatecounter', 800);
    return $events;

  }

  /**
   * Subscriber Callback for the event.
   * @param CustomEvent $event
   */
  public function updatecounter(CustomEvent $event) {
    // drupal_set_message("The Example Event has been subscribed, which has bee dispatched on submit of the form with " . $event->getReferenceID() . " as Reference");
        $id = $event->getReferenceID();  // Primary key of customurl table
        $connection = \Drupal::database();
        $query      = db_select('customurls', 'curl');
        $query->condition('curl.id', $id)->fields('curl');
        $result  = $query->execute();
        $results = $result->fetchAssoc(\PDO::FETCH_OBJ);

        $counter = $results['counter'] + 1;

        $currentAccount = \Drupal::currentUser();
        $user_id = $currentAccount->id();
        $user_counter_query      = db_select('customurl_user_counter', 'cuc');
        $user_counter_query->condition('cuc.url_id', $id)->condition('cuc.user_id', $user_id)->fields('cuc');
        $counter_query_result  = $user_counter_query->execute();
        $counter_results = $counter_query_result->fetchAssoc(\PDO::FETCH_OBJ);
        
        //Update url Table
        \Drupal::database()->update('customurls')->fields(array(
            'counter' => $counter
        ))->condition('id', $id)->execute();
        $message = "Counter updated.";


        if($counter_results['id'] != ""){
          $user_counter = $counter_results['user_counter'] + 1;
          //Update user url counter Table
        \Drupal::database()->update('customurl_user_counter')->fields(array(
            'user_counter' => $user_counter
        ))->condition('id', $counter_results['id'])->execute();
        $message = "User Counter updated.";
        }else{
          \Drupal::database()->insert('customurl_user_counter')->fields(array(
              'url_id' => $id,
              'user_id' => $user_id,
              'user_counter' => 1,
          ))->execute();
        }

        return $message;

  }

  // /**
  //  * Subscriber Callback for the event.
  //  * @param ConfigCrudEvent $event
  //  */
  // public function onSavingConfig(ConfigCrudEvent $event) {
  //   drupal_set_message("You have saved a configuration of " . $event->getConfig()->getName());
  // }
}

