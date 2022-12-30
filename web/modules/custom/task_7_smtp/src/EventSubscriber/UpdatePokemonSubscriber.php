<?php

namespace Drupal\task_7_smtp\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\task_7_smtp\Event\UpdatePokemonNodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatePokemonSubscriber implements EventSubscriberInterface {

  /**
   * List of notified emails
   *
   * @var array
   */
  protected array $emails;

  /**
   * Defines messenger service
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Defines mail manager service
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   */
  public function __construct(ConfigFactoryInterface $config_factory, MessengerInterface $messenger, MailManagerInterface $mail_manager) {
    $this->emails = $this->getEmails($config_factory);
    $this->messenger = $messenger;
    $this->mailManager = $mail_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[UpdatePokemonNodeEvent::MAIL_NOTIFY][] = ['sendMailingNotification'];
    $events[UpdatePokemonNodeEvent::TELEGRAM_NOTIFY][] = ['sendTelegramNotification'];

    return $events;
  }

  /**
   * Send mailing notification
   *
   * @param \Drupal\task_7_smtp\Event\UpdatePokemonNodeEvent $event
   *
   * @return void
   */
  public function sendMailingNotification(UpdatePokemonNodeEvent $event): void {
    $variables = $event->getVariables();

    $params['title'] = 'Pokemon updated';
    $params['message'] = $variables['message'];

    foreach ($this->emails as $email) {
      $this->mailManager->mail(
        $variables['module'],
        $variables['key'],
        $email,
        $variables['langcode'],
        $params
      );
    }

    $this->messenger
      ->addMessage('Your emails have been sent.');
  }

  public function sendTelegramNotification(UpdatePokemonNodeEvent $event) {
    $this->messenger
      ->addMessage('Your telegram');
    $variables = $event->getVariables();

    $telegram_bot = \Drupal::entityTypeManager()
      ->getStorage('telegram_bot')
      ->load('notification');

    /** @var \Telegram\Bot\Api $telegram */
    $telegram = \Drupal::service('drupal_telegram_sdk.bot_api')
      ->getTelegram($telegram_bot);

    $telegram->sendMessage([
      'chat_id' => '-808709839',
      'text' => $variables['message'],
    ]);
  }

  /**
   * Convert email to array
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *
   * @return array
   */
  private function getEmails(ConfigFactoryInterface $config_factory): array {
    $emails = $config_factory->get('task_7_smtp.mailing_notification_list')
      ->get('emails');

    return array_map(fn($email) => trim($email), explode(',', $emails));
  }

}
