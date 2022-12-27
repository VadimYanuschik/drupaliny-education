<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Plugin\AdvancedQueue\JobType\JobTypeBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use PokePHP\PokeApi;
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
   * @var \PokePHP\PokeApi
   */
  protected $pokemonApi;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->fileRepository = $container->get('file.repository');
    $instance->pokemonApi = new PokeApi();

    return $instance;
  }

  /**
   * Save entity to storage
   *
   * @param string $entity_type
   * @param array $fields
   *
   * @return \Drupal\Core\Entity\EntityInterface
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createEntity(string $entity_type, array $fields): EntityInterface {
    $entity = $this->entityTypeManager->getStorage($entity_type)
      ->create($fields)
      ->enforceIsNew();

    $entity->save();

    return $entity;
  }

  /**
   * Load entity from storage
   *
   * @param string $entity_type
   * @param array $fields
   *
   * @return false
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function loadEntity(string $entity_type, array $fields): EntityInterface|bool {
    $entities = $this->entityTypeManager->getStorage($entity_type)
      ->loadByProperties($fields);

    if (empty($entities)) {
      return FALSE;
    }

    return array_pop($entities);
  }

  /**
   * Import entity to storage
   *
   * @param string $entity_type
   * @param array $fields
   *
   * @return int|string
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function importEntity(string $entity_type, array $fields): int|string {
    $entity = $this->loadEntity($entity_type, $fields);

    if (!$entity) {
      $entity = $this->createEntity($entity_type, $fields);
    }

    return $entity->id();
  }

}
