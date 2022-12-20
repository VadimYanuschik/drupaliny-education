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
    $entity_type = self::STORAGE_TAXONOMY;
    $fields = ['vid' => 'colors', 'name' => $payload['color']['name']];
    $taxonomies['field_colors'][] = $this->importEntity($entity_type, $fields);

    $fields['vid'] = 'egg_groups';
    $fields['name'] = $payload['egg_groups'][0]['name'];
    $taxonomies['field_egg_groups'][] = $this->importEntity($entity_type, $fields);

    if (isset($payload['habitat'])) {
      $fields['vid'] = 'habitats';
      $fields['name'] = $payload['habitat']['name'];
      $taxonomies['field_habitats'][] = $this->importEntity($entity_type, $fields);
    }

    $fields['vid'] = 'shapes';
    $fields['name'] = $payload['shape']['name'];
    $taxonomies['field_shapes'][] = $this->importEntity($entity_type, $fields);

    $fields['vid'] = 'species';
    $fields['name'] = $payload['species']['name'];
    $taxonomies['field_species'][] = $this->importEntity($entity_type, $fields);

    $fields['vid'] = 'abilities';
    foreach ($payload['abilities'] as $ability) {
      $fields['name'] = $ability['ability']['name'];
      $taxonomies['field_abilities'][] = $this->importEntity($entity_type, $fields);
    }

    $fields['vid'] = 'forms';
    foreach ($payload['forms'] as $form) {
      $fields['name'] = $form['name'];
      $taxonomies['field_forms'][] = $this->importEntity($entity_type, $fields);
    }

    $fields['vid'] = 'types';
    foreach ($payload['types'] as $type) {
      $fields['name'] = $type['type']['name'];
      $taxonomies['field_types'][] = $this->importEntity($entity_type, $fields);
    }

    return $taxonomies;
  }

}
