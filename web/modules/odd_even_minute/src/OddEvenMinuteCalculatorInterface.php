<?php

namespace Drupal\odd_even_minute;

/**
 * Calculate is current time minute odd or even
 */
interface OddEvenMinuteCalculatorInterface {

  /**
   * Make calculation based on current time
   * @return bool
   */
  public function calculate(): bool;

}
