<?php

namespace Drupal\odd_even_minute\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
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
   * Drupal Config service
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected ImmutableConfig $config;

  /**
   * The Entity Type Manager class
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->oddEvenMinuteCalculator = $container->get('odd_even_minute.calculate_service');
    $instance->config = $container->get('config.factory')
      ->get('odd_even_minute.admin_cache_settings');
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $node = $this->getConfigNode();

    if ($node) {
      $rendered_node = $this->entityTypeManager->getViewBuilder('node')
        ->view($node, 'teaser');
    }

    return [
      '#theme' => 'odd_even_minute_template',
      '#rendered_node' => $rendered_node,
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

  /**
   * {@inheritdoc}
   */
  public function getCacheTags(): array {
    return Cache::mergeTags(
      parent::getCacheTags(),
      ['config:odd_even_minute.admin_cache_settings']
    );
  }

  /**
   * Return cached node
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function getConfigNode(): ?EntityInterface {
    $isOddMinute = $this->oddEvenMinuteCalculator->calculate();

    $field_odd = $this->config->get('field_odd');
    $field_even = $this->config->get('field_even');

    if ($isOddMinute && $field_odd) {
      $node = $this->entityTypeManager->getStorage('node')
        ->load($field_odd);
    }

    if (!$isOddMinute && $field_even) {
      $node = $this->entityTypeManager->getStorage('node')
        ->load($field_even);
    }

    return $node ?: NULL;
  }

}
