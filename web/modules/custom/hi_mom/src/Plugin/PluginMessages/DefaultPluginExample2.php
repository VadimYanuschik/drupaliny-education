<?php

/**
 * @file
 * Пример плагина.
 */

namespace Drupal\hi_mom\Plugin\PluginMessages;

use Drupal\hi_mom\PluginMessagesPluginBase;

/**
 * @PluginMessages(
 *   id="default_plugin_example_2",
 * )
 */
class DefaultPluginExample2 extends PluginMessagesPluginBase {

  /**
   * Возвращаем сообщение данного плагина.
   */
  public function getMessage(): string {
    return 'This is message from Example #2';
  }

  /**
   * {@inheritdoc}
   */
  public function getMessageType() {
    return 'error';
  }

  /**
   * {@inheritdoc}
   */
  public function getPages() {
    return [
      '/node/*',
      '/contact',
      '<front>',
    ];
  }

}
