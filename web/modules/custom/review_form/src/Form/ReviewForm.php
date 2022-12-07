<?php

namespace Drupal\review_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\RendererInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ReviewForm extends FormBase {

  /**
   * {@inheritdoc}
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected RendererInterface $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container);

    $instance->messenger = $container->get('messenger');
    $instance->renderer = $container->get('renderer');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_review_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['system_messages'] = [
      '#markup' => '<div id="form-system-messages"></div>',
      '#weight' => -100,
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Your name'),
    ];

    $form['review'] = [
      '#type' => 'textarea',
      '#required' => TRUE,
      '#title' => $this->t('Your review'),
    ];

    $form['points'] = [
      '#type' => 'number',
      '#required' => TRUE,
      '#default_value' => 5,
      '#min' => 1,
      '#max' => 10,
      '#title' => $this->t('Your points'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::validateAjaxForm',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
          'message' => t('Verifying form...'),
        ],
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();

    if (strlen($form_values['name']) < 5) {
      $form_state->setErrorByName('name', $this->t('Name is too short'));
    }

    if (strlen($form_values['review']) < 9) {
      $form_state->setErrorByName('review', $this->t('Review is too short'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');

    $this->messenger->addMessage("$name, thanks for your review");
  }

  /**
   * {@inheritdoc}
   */
  public function validateAjaxForm(array &$form, FormStateInterface $form_state): AjaxResponse {
    $ajax_response = new AjaxResponse();
    $message = [
      '#theme' => 'status_messages',
      '#message_list' => $this->messenger()->all(),
      '#status_headings' => [
        'status' => $this->t('Status message'),
        'error' => $this->t('Error message'),
        'warning' => $this->t('Warning message'),
      ],
    ];

    $this->messenger->deleteAll();

    $messages = $this->renderer->render($message);
    $ajax_response->addCommand(new HtmlCommand('#form-system-messages', $messages));
    return $ajax_response;
  }

}
