<?php

namespace Drupal\odd_even_minute\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


class OddEvenMinuteConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'odd_even_minute_admin_cache_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'odd_even_minute.admin_cache_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('odd_even_minute.admin_cache_settings');

    $v = $config->get('field_odd');

    $form['field_odd'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#default_value' => $config->get('field_odd'),
    ];

    $form['field_even'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#default_value' => $config->get('field_even'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('odd_even_minute.admin_cache_settings')
      ->set('field_odd', $form_state->getValue('field_odd'))
      ->set('field_even', $form_state->getValue('field_even'))
      ->save();
  }

}
