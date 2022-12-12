<?php

namespace Drupal\odd_even_minute;

use Drupal\Component\Datetime\TimeInterface;

class OddEvenMinuteCalculatorService implements OddEvenMinuteCalculatorInterface {

  /**
   * The Time service
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected TimeInterface $dateTime;

  /**
   * The constructor for Time class
   *
   * @param \Drupal\Component\Datetime\TimeInterface $date_time
   */
  public function __construct(TimeInterface $date_time) {
    $this->dateTime = $date_time;
  }

  /**
   * {@inheritdoc}
   */
  public function calculate(): bool {
    return floor(($this->dateTime->getCurrentTime()) / 60) % 2 === 0;
  }

}
