<?php

namespace Drupal\task_4\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CollectPhoneSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'task_4.collect_phone.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'collect_phone_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('task_4.collect_phone.settings');

    $form['default_phone_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default phone number'),
      '#default_value' => $config->get('phone_number'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('task_4.collect_phone.settings')
      ->set('phone_number', $values['default_phone_number'])
      ->save();
  }

}
