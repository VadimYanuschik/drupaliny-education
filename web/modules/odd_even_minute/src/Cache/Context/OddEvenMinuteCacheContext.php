<?php

namespace Drupal\odd_even_minute\Cache\Context;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;
use Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface;

/**
 * Cache context ID: 'odd_even_request'.
 */
class OddEvenMinuteCacheContext implements CacheContextInterface {

  /**
   * @var \Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface
   */
  protected OddEvenMinuteCalculatorInterface $oddEvenMinuteCalculator;

  /**
   * @param \Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface $oddEvenMinuteCalculator
   */
  public function __construct(OddEvenMinuteCalculatorInterface $oddEvenMinuteCalculator) {
    $this->oddEvenMinuteCalculator = $oddEvenMinuteCalculator;
  }

  /**
   * {@inheritDoc}
   */
  public static function getLabel() {
    return t('Odd or even minute?');
  }

  /**
   * {@inheritDoc}
   */
  public function getContext() {
    return $this->oddEvenMinuteCalculator->calculate() ? 'even' : 'odd';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata(): CacheableMetadata {
    return new CacheableMetadata();
  }

}