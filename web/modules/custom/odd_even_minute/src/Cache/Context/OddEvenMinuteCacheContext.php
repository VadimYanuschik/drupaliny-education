<?php

namespace Drupal\odd_even_minute\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface;

/**
 * Cache context ID: 'odd_even_minute'.
 */
class OddEvenMinuteCacheContext implements CacheContextInterface {

  /**
   * Odd Even Minute Calculator service
   *
   * @var \Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface
   */
  protected OddEvenMinuteCalculatorInterface $oddEvenMinuteCalculator;

  /**
   * The constructor for Odd Even Minute Calculator service
   *
   * @param \Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface $oddEvenMinuteCalculator
   */
  public function __construct(OddEvenMinuteCalculatorInterface $oddEvenMinuteCalculator) {
    $this->oddEvenMinuteCalculator = $oddEvenMinuteCalculator;
  }

  /**
   * {@inheritdoc}
   */
  public static function getLabel() {
    return t('Odd or even minute?');
  }

  /**
   * {@inheritdoc}
   */
  public function getContext() {
    return $this->oddEvenMinuteCalculator->calculate() ? 'odd' : 'even';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata(): CacheableMetadata {
    return new CacheableMetadata();
  }

}
