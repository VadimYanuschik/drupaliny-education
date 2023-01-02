<?php

namespace Drupal\task_7_smtp\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\task_7_smtp\Event\UpdatePokemonNodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\drupal_telegram_sdk\TelegramBotApi;

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
   * Defines Telegram API interface
   *
   * @var \Telegram\Bot\Api
   */
  protected $telegram;

  /**
   * Telegram chat id
   *
   * @var string
   */
  protected string $telegramChatId;

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(ConfigFactoryInterface $config_factory, MessengerInterface $messenger, MailManagerInterface $mail_manager, EntityTypeManagerInterface $entity_type_manager, TelegramBotApi $telegram) {
    $this->emails = $this->getEmails($config_factory);
    $this->messenger = $messenger;
    $this->mailManager = $mail_manager;
    $this->telegramChatId = $config_factory->get('task_7_smtp.telegram_bot_settings')->get('chat_id');

    $telegram_bot = $entity_type_manager->getStorage('telegram_bot')
      ->load('notification');
    $this->telegram = $telegram->getTelegram($telegram_bot);

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

  /**
   * Send telegram notification
   *
   * @param \Drupal\task_7_smtp\Event\UpdatePokemonNodeEvent $event
   *
   * @return void
   * @throws \Telegram\Bot\Exceptions\TelegramSDKException
   */
  public function sendTelegramNotification(UpdatePokemonNodeEvent $event): void {
    $variables = $event->getVariables();

    $this->telegram->sendMessage([
      'chat_id' => $this->telegramChatId,
      'text' => $variables['message'],
    ]);

    $this->messenger
      ->addMessage('Your telegram message was sended');
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
