<?php

namespace Drupal\task_7_smtp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class TelegramBotForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'task_7_smtp.telegram_bot_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'telegram_bot_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('task_7_smtp.telegram_bot_settings');

    $form['chat_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Chat id'),
      '#description' => $this->t('Write chat id to which you want to send notification'),
      '#default_value' => $config->get('chat_id'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);

    $this->config('task_7_smtp.telegram_bot_settings')
      ->set('chat_id', $form_state->getValue('chat_id'))
      ->save();
  }

}
