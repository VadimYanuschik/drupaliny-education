<?php

namespace Drupal\probation;

use Drupal\Core\Plugin\PluginBase;

abstract class ParagraphBehaviorBase extends PluginBase implements ParagraphBehaviorInterface {

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritDoc}
   */
  public function getId() {
    # Возвращаем значение аннотации $id.
    return $this->pluginDefinition['id'];
  }

  /**
   * {@inheritDoc}
   */
  public function getLabel() {
    # Возвращаем значение аннотации $id.
    return $this->pluginDefinition['label'];
  }
  
}
