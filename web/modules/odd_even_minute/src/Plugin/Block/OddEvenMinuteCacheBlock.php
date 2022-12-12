<?php

namespace Drupal\odd_even_minute\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @Block(
 *  id = "odd_even_minute_cache",
 *  admin_label = @Translation("Odd even minute cache"),
 *  )
 */
class OddEvenMinuteCacheBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Odd Even Minute Calculator service
   *
   * @var \Drupal\odd_even_minute\OddEvenMinuteCalculatorInterface
   */
  protected OddEvenMinuteCalculatorInterface $oddEvenMinuteCalculator;

  /**
   * The constructor for Odd Even Minute Calculator service
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->oddEvenMinuteCalculator = $container->get('odd_even_minute.calculate_service');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#theme' => 'odd_even_minute_template',
      '#text' => $this->oddEvenMinuteCalculator->calculate() ? 'even 1' : 'odd 2',
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
