<?php

namespace Drupal\task_7_smtp\Event;

use Drupal\Component\EventDispatcher\Event;

class UpdatePokemonNodeEvent extends Event {

  const UPDATE_POKEMON = 'mailing_on_pokemon_updating';

  /**
   * Variables from preprocess.
   */
  protected $variables;

  /**
   * DummyFrontpageEvent constructor.
   */
  public function __construct($variables) {
    $this->variables = $variables;
  }

  /**
   * Returns variables array from preprocess.
   */
  public function getVariables() {
    return $this->variables;
  }

}
