<?php

namespace Drupal\odd_even_minute\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;

/**
 * @Block(
 *  id = "odd_even_minute_cache",
 *  admin_label = @Translation("Odd even minute cache"),
 *  )
 */
class OddEvenMinuteCacheBlock extends BlockBase {

  public function build(): array {
    return [
      '#markup' => odd_even_minute_check() ? 'even 1' : 'odd 2',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts(): array {
    return Cache::mergeContexts(
      parent::getCacheContexts(),
      ['odd_even_minute']
    );
  }

}
