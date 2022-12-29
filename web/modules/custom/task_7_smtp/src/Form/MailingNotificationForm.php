<?php

namespace Drupal\task_7_smtp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailingNotificationForm extends ConfigFormBase {

  /**
   * Defines email validator service
   *
   * @var \Drupal\Component\Utility\EmailValidator
   */
  protected $emailValidator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container);
    $instance->emailValidator = $container->get('email.validator');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'mailing_notification_list',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'mailing_notification_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('mailing_notification_list');

    $form = parent::buildForm($form, $form_state);

    $form['emails'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Emails'),
      '#description' => $this->t('Write email and separate them by comma'),
      '#default_value' => $config->get('emails'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $emails = explode(',', $form_state->getValue('emails'));

    foreach ($emails as $email) {
      if (!$this->emailValidator->isValid(trim($email))) {
        $form_state->setErrorByName('emails', 'Invalid email');
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);

    $this->config('mailing_notification_list')
      ->set('emails', $form_state->getValue('emails'))
      ->save();
  }

}
