<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;
use Drupal\Component\Serialization\Json;
use Drupal\media\Entity\Media;

/**
 * @AdvancedQueueJobType(
 *  id = "pokemon_import_job",
 *  label = @Translation("PokemonImportJob"),
 *  max_retries = 3,
 *  retry_delay = 60,
 * )
 */
class PokemonImportJob extends AbstractImportJob {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job): JobResult {
    try {
      $payload = $job->getPayload();

      if (isset($payload)) {
        $pokemon_id = explode('/pokemon/', $payload['url'])[1];
        $pokemonData = Json::decode($this->pokemonApi->pokemon($pokemon_id));
        $pokemonSpecieData = Json::decode($this->pokemonApi->pokemonSpecies($pokemon_id));
        $pokemonData = array_merge($pokemonData, $pokemonSpecieData);

        $taxonomies = $this->importPokemonTaxonomies($pokemonData);

        $image_id = $this->uploadImage($pokemonData['name'], $pokemonData['sprites']['other']['official-artwork']['front_default']);

        $fields = [
          'type' => 'pokemon',
          'title' => $pokemonData['name'],
          'field_name' => $pokemonData['name'],
          'field_base_experience' => $pokemonData['base_experience'],
          'field_weight' => $pokemonData['weight'],
          'field_height' => $pokemonData['height'],
          'field_image' => $image_id,
        ];

        $fields = array_merge($fields, $taxonomies);

        $this->importEntity('node', $fields);

        return JobResult::success('successful');
      }
      return JobResult::failure('no payload', 3, 60);
    } catch (\Exception $e) {
      return JobResult::failure($e->getMessage());
    }
  }

  /**
   * Import pokemons taxonomies
   *
   * @param array $payload
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function importPokemonTaxonomies(array $payload): array {
    $taxonomies = [];

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

        $taxonomies['field_' . $taxonomyPlural][] = $this->importEntity('taxonomy_term', $fields);
      }
    }

    foreach ($nested_collection_taxonomy as $taxonomyPlural => $taxonomy) {
      $fields['vid'] = $taxonomyPlural;

      if ($payload[$taxonomyPlural]) {
        foreach ($payload[$taxonomyPlural] as $tax) {
          $fields['name'] = $tax[$taxonomy]['name'];
          $taxonomies['field_' . $taxonomyPlural][] = $this->importEntity('taxonomy_term', $fields);
        }
      }
    }

    foreach ($collection_taxonomy as $taxonomyPlural) {
      $fields['vid'] = $taxonomyPlural;

      if ($payload[$taxonomyPlural]) {
        foreach ($payload[$taxonomyPlural] as $tax) {
          $fields['name'] = $tax['name'];
          $taxonomies['field_' . $taxonomyPlural][] = $this->importEntity('taxonomy_term', $fields);
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
   * @return string
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function uploadImage(string $name, string $image_url): string {
    $data = file_get_contents($image_url);
    $destination = 'public://' . $name . '_' . time() . '.png';

    $file = $this->fileRepository->writeData($data, $destination);
    $file->save();

    $media = Media::create([
      'bundle' => 'image',
      'uid' => '0',
      'field_media_image' => [
        'target_id' => $file->id(),
      ],
    ]);

    $media->setPublished()->save();

    return $media->id();
  }

}
