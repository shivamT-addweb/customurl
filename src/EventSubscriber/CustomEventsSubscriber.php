<?php
  
namespace Drupal\customurl\EventSubscriber;
  
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\customurl\CustomEvent;
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
        $id = $event->getReferenceID();
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

  // /**
  //  * Subscriber Callback for the event.
  //  * @param ConfigCrudEvent $event
  //  */
  // public function onSavingConfig(ConfigCrudEvent $event) {
  //   drupal_set_message("You have saved a configuration of " . $event->getConfig()->getName());
  // }
}