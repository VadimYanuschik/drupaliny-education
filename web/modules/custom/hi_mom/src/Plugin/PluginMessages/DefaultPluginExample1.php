<?php

/**
 * @file
 * Example of Plugin
 */

namespace Drupal\hi_mom\Plugin\PluginMessages;

use Drupal\hi_mom\Annotation\PluginMessages;
use Drupal\hi_mom\PluginMessagesPluginBase;

/**
 * @PluginMessages(
 *   id="default_plugin_example_1",
 * )
 */
class DefaultPluginExample1 extends PluginMessagesPluginBase {

  /**
   * Возвращаем сообщение данного плагина.
   */
  public function getMessage(): string {
    return "This is message from Example #1, Hi mom!";
  }

}


