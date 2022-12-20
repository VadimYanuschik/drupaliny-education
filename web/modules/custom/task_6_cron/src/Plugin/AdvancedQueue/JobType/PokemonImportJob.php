<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;

/**
 * @AdvancedQueueJobType(
 *  id = "pokemon_import_job",
 *  label = @Translation("PokemonImportJob"),
 *  max_retries = 1,
 *  retry_delay = 10,
 * )
 */
class PokemonImportJob extends AbstractImportJob {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job) {
    try {
      $payload = $job->getPayload()[0];

      if (isset($payload)) {
        $taxonomies = $this->importPokemonTaxonomies($payload);

        $fields = [
          'type' => 'pokemon',
          'title' => $payload['name'],
          'field_name' => $payload['name'],
          'field_base_experience' => $payload['base_experience'],
          'field_weight' => $payload['weight'],
          'field_height' => $payload['height'],
        ];

        $fields = array_merge($fields, $taxonomies);

        $entityID = $this->importEntity(self::STORAGE_NODE, $fields);

        if ($entityID) {
          return JobResult::success('successful');
        }
      }
      return JobResult::failure('no payload');
    } catch (\Exception $e) {
      return JobResult::failure($e->getMessage());
    }
  }

  private function importPokemonTaxonomies(array $payload): array {
    $taxonomies = [];
    $entity_type = self::STORAGE_TAXONOMY;

    $object_taxonomy = [
      'colors' => 'color',
      'habitats' => 'habitat',
      'shapes' => 'shape',
      'species' => 'species',
    ];

    $nested_collection_taxonomy = [
      'abilities' => 'ability',
      'types' => 'type',
    ];

    $collection_taxonomy = [
      'forms',
      'egg_groups',
    ];

    foreach ($object_taxonomy as $taxonomyPlural => $taxonomy) {
      $fields = [
        'vid' => $taxonomyPlural,
        'name' => $payload[$taxonomy]['name'],
      ];

      if ($payload[$taxonomy]) {
        $taxonomies['field_' . $taxonomyPlural][] = $this->importEntity($entity_type, $fields);
      }
    }

    foreach ($nested_collection_taxonomy as $taxonomyPlural => $taxonomy) {
      $fields['vid'] = $taxonomyPlural;

      if ($payload[$taxonomyPlural]) {
        foreach ($payload[$taxonomyPlural] as $tax) {
          $fields['name'] = $tax[$taxonomy]['name'];
          $taxonomies['field_' . $taxonomyPlural][] = $this->importEntity($entity_type, $fields);
        }
      }
    }

    foreach ($collection_taxonomy as $taxonomyPlural) {
      $fields['vid'] = $taxonomyPlural;

      if ($payload[$taxonomyPlural]) {
        foreach ($payload[$taxonomyPlural] as $tax) {
          $fields['name'] = $tax['name'];
          $taxonomies['field_' . $taxonomyPlural][] = $this->importEntity($entity_type, $fields);
        }
      }
    }

    return $taxonomies;
  }

}
