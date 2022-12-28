<?php

namespace Drupal\task_7_smtp\EventSubscriber;

use Drupal\task_7_smtp\Event\UpdatePokemonNodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UpdatePokemonSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents(): array {
    return [
      UpdatePokemonNodeEvent::UPDATE_POKEMON => ['sendMailingNotification']
    ];
  }

  public function sendMailingNotification(UpdatePokemonNodeEvent $event): void {
    /** @var \Drupal\Core\Messenger\MessengerInterface $messenger */
    $messenger = \Drupal::service('messenger');
    $messenger->addMessage('Event for preprocess HTML called');
  }

}
