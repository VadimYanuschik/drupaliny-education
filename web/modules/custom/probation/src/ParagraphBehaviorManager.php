<?php

namespace Drupal\probation;

use Drupal\Component\Plugin\Factory\DefaultFactory;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

class ParagraphBehaviorManager extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/paragraphs/Behaviour',
      $namespaces,
      $module_handler,
      'Drupal\probation\ParagraphBehaviorInterface',
      'Drupal\probation\Annotation\ParagraphsBehavior'
    );

    # Задаем ключ для кэша плагинов.
    $this->setCacheBackend($cache_backend, 'plugin_messages');
    $this->factory = new DefaultFactory($this->getDiscovery());
  }

}
