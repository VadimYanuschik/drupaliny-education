<?php

namespace Drupal\task_7_smtp\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailingNotificationForm extends ConfigFormBase {

  /**
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
      'task_7_smtp.mailing_notified_settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'task_7_smtp_mailing_notified_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $config = $this->config('task_7_smtp.mailing_notified_settings');

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

    $this->config('task_7_smtp.mailing_notified_settings')
      ->set('emails', $form_state->getValue('emails'))
      ->save();
  }

}
