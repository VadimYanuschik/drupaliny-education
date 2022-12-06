<?php

namespace Drupal\odd_even_minute\Cache\Context;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CacheContextInterface;

/**
 * Cache context ID: 'odd_even_request'.
 */
class OddEvenMinuteCacheContext implements CacheContextInterface {

  /**
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected TimeInterface $time;

  /**
   * @param \Drupal\Component\Datetime\TimeInterface $time
   */
  public function __construct(TimeInterface $time) {
    $this->time = $time;
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
    return odd_even_minute_check() ? 'even' : 'odd';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata(): CacheableMetadata {
    return new CacheableMetadata();
  }

}
