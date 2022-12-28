<?php

namespace Drupal\task_7_smtp\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\task_7_smtp\Event\UpdatePokemonNodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatePokemonSubscriber implements EventSubscriberInterface {

  /**
   * @var array
   */
  protected array $emails;

  /**
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->emails = $this->getEmails($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      UpdatePokemonNodeEvent::UPDATE_POKEMON => ['sendMailingNotification'],
    ];
  }

  /**
   * Send mailing notification
   *
   * @param \Drupal\task_7_smtp\Event\UpdatePokemonNodeEvent $event
   *
   * @return void
   */
  public function sendMailingNotification(UpdatePokemonNodeEvent $event): void {

  }

  /**
   * Convert email to array
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *
   * @return array
   */
  private function getEmails(ConfigFactoryInterface $config_factory): array {
    $emails = $config_factory->get('task_7_smtp.mailing_notified_settings')
      ->get('emails');

    return array_map(fn($email) => trim($email), explode(',', $emails));
  }

}
