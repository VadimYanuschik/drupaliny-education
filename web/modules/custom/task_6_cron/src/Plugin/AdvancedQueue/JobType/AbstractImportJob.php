<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Plugin\AdvancedQueue\JobType\JobTypeBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractImportJob extends JobTypeBase implements ContainerFactoryPluginInterface {

  /**
   * The Entity Type Manager service
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Defines File Repository service
   *
   * @var \Drupal\file\FileRepositoryInterface
   */
  protected $fileRepository;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->fileRepository = $container->get('file.repository');

    return $instance;
  }

  /**
   * Save entity to storage
   *
   * @param string $entity_type
   * @param array $fields
   * @param bool $return_as_object
   *
   * @return int|string
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createEntity(string $entity_type, array $fields, bool $return_as_object = FALSE): int|string {
    $entity = $this->entityTypeManager->getStorage($entity_type)
      ->create($fields)
      ->enforceIsNew();

    $is_saved = $entity->save();

    if ($return_as_object) {
      return $entity->id();
    }

    return $is_saved;
  }

  /**
   * Load entity from storage
   *
   * @param string $entity_type
   * @param array $fields
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function loadEntity(string $entity_type, array $fields): array {
    return $this->entityTypeManager->getStorage($entity_type)
      ->loadByProperties($fields);
  }

  /**
   * Import entity to storage
   *
   * @param string $entity_type
   * @param array $fields
   *
   * @return int
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importEntity(string $entity_type, array $fields): int {
    $existing = $this->loadEntity($entity_type, $fields);

    if (!$existing) {
      $existing = $this->createEntity($entity_type, $fields, TRUE);
    }

    return array_pop($existing)->id();
  }

}
