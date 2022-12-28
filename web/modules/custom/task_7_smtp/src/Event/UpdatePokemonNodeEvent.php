<?php

namespace Drupal\task_7_smtp\Event;

use Drupal\Component\EventDispatcher\Event;

class UpdatePokemonNodeEvent extends Event {

  const UPDATE_POKEMON = 'task_7_smtp.node_update';

  /**
   * Mails that will be notified
   *
   * @var array
   */
  protected array $mails;

  public function __construct() {
    $this->mails = [];
  }

}
