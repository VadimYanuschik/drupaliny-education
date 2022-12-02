<?php

namespace Drupal\probation;

use Drupal\Component\Plugin\PluginInspectionInterface;

interface ParagraphBehaviorInterface extends PluginInspectionInterface {

  /**
   * Get Plugin ID
   */
  public function getId();

  /**
   * Get Plugin Label
   */
  public function getLabel();

  /**
   * Get Plugin Description
   */
  public function getDescription();

  /**
   * Get Plugin Weight
   */
  public function getWeight();

}
