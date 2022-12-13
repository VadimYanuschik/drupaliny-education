<?php

namespace Drupal\odd_even_minute\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OddEvenMinuteConfigurationForm extends ConfigFormBase {

  /**
   * The Entity Type Manager class
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Add following dependencies:
   * - Drupal Entity Type Manager service
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container);
    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

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

    $field_odd = $this->config('odd_even_minute.admin_cache_settings')
      ->get('field_odd');
    $field_even = $this->config('odd_even_minute.admin_cache_settings')
      ->get('field_even');

    if ($field_odd) {
      $field_odd = $this->entityTypeManager->getStorage('node')
        ->load($field_odd);
    }

    if ($field_even) {
      $field_even = $this->entityTypeManager->getStorage('node')
        ->load($field_even);
    }

    $form['field_odd'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#default_value' => $field_odd,
    ];

    $form['field_even'] = [
      '#type' => 'entity_autocomplete',
      '#target_type' => 'node',
      '#tags' => FALSE,
      '#default_value' => $field_even,
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
