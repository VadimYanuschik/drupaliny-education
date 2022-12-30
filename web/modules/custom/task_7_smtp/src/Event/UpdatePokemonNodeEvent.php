<?php

namespace Drupal\task_7_smtp\Event;

use Drupal\Component\EventDispatcher\Event;

class UpdatePokemonNodeEvent extends Event {

  const UPDATE_POKEMON = 'mailing_on_pokemon_updating';

  /**
   * Event variables
   *
   * @var array
   */
  protected array $variables;

  /**
   * @param $variables
   */
  public function __construct($variables) {
    $this->variables = $variables;
  }

  /**
   * Returns event variables
   *
   * @return array
   */
  public function getVariables(): array {
    return $this->variables;
  }

}
