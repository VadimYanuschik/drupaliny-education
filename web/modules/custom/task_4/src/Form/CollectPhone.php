<?php

namespace Drupal\task_4\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CollectPhone extends FormBase {

  /**
   * {@inheritdoc}.
   */
  public function getFormId() {
    return 'collect_phone';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Your phone DUDE'),
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Your name'),
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Send name and phone'),
      '#button_type' => 'primary',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('name')) < 5) {
      $form_state->setErrorByName('name', $this->t('Name is too short.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()
      ->addMessage($this->t('Thank you @name, your phone number is @number', [
        '@name' => $form_state->getValue('name'),
        '@number' => $form_state->getValue('phone_number'),
      ]));
  }

}
