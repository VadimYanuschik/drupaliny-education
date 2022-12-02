<?php

namespace Drupal\probation\Annotation;

/**
 * @Annotation
 */
class ParagraphsBehavior {

  /**
   * The plugin ID
   * @var string
   */
  public string $id;

  /**
   * The plugin Label
   * @var string
   */
  public string $label;

  /**
   * The plugin Description
   * @var string
   */
  public string $description;

  /**
   * The plugin Weight
   * @var int
   */
  public int $weight;

}
