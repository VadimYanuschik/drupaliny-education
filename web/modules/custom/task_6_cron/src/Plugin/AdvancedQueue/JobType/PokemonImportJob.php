<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;
use Drupal\media\Entity\Media;

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
  public function process(Job $job): JobResult {
    try {
      $payload = $job->getPayload()[0];

      if (isset($payload)) {
        $taxonomies = $this->importPokemonTaxonomies($payload);

        $image_id = $this->uploadImage($payload['name'], $payload['sprites']['other']['official-artwork']['front_default']);

        $fields = [
          'type' => 'pokemon',
          'title' => $payload['name'],
          'field_name' => $payload['name'],
          'field_base_experience' => $payload['base_experience'],
          'field_weight' => $payload['weight'],
          'field_height' => $payload['height'],
          'field_image' => $image_id,
        ];

        $fields = array_merge($fields, $taxonomies);

        $this->importEntity(self::STORAGE_NODE, $fields);

        return JobResult::success('successful');
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
      if ($payload[$taxonomy]) {
        $fields = [
          'vid' => $taxonomyPlural,
          'name' => $payload[$taxonomy]['name'],
        ];

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

  /**
   * Upload image to media storage
   *
   * @param string $name
   * @param string $image_url
   *
   * @return array
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function uploadImage(string $name, string $image_url): array {
    $data = file_get_contents($image_url);
    $destination = 'public://' . $name . '_' . time() . '.png';

    $media = Media::create([
      'bundle' => 'image',
      'uid' => '0',
      'field_media_image' => [
        'target_id' => $this->fileRepository->writeData($data, $destination)
          ->id(),
      ],
    ]);

    $media->setPublished()->save();

    return [$media->id()];
  }

}
