<?php

namespace Drupal\customurl;

use Symfony\Component\EventDispatcher\Event;

class CustomEvent extends Event {

  const COUNTER = 'event.submit';
  protected $referenceID;

  public function __construct($referenceID)
  {
    $this->referenceID = $referenceID;
  }

  public function getReferenceID()
  {
    return $this->referenceID;
  }

}