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
        ];

        $fields = array_merge($fields, $taxonomies);

        return $this->importEntity(self::STORAGE_NODE, $fields);
      }
      return JobResult::failure('no payload');
    } catch (\Exception $e) {
      return JobResult::failure($e->getMessage());
    }
  }

  /**
   * Returns list of related taxonomies
   *
   * @return string[]
   */
  private function getTaxonomiesList(): array {
    return [
      'abilities' => 'ability',
      'colors' => 'color',
      'egg_groups' => 'egg_group',
      'forms' => 'form',
      'genders' => 'gender',
      'habitats' => 'habitat',
      'shapes' => 'shape',
      'species' => 'specie',
      'types' => 'type',
    ];
  }

  /**
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  private function importPokemonTaxonomies(array $payload): array {
    $taxonomiesList = $this->getTaxonomiesList();
    $pokemonTaxonomies = [];

    foreach ($taxonomiesList as $taxonomyPlural => $taxonomy) {
      if (array_key_exists($taxonomyPlural, $payload)) {
        foreach ($payload[$taxonomyPlural] as $taxonomy_single => $value) {
          $fields = [
            'vid' => $taxonomyPlural,
            'name' => $payload[$taxonomyPlural][$taxonomy_single][$taxonomy]['name'],
          ];

          $this->importEntity(self::STORAGE_TAXONOMY, $fields);
          $loaded = $this->loadEntity(self::STORAGE_TAXONOMY, $fields);
          $taxonomyID = array_pop($loaded)->id();

          $pokemonTaxonomies['field_' . $taxonomy][] = $taxonomyID;
        }
      }
    }

    return $pokemonTaxonomies;
  }

}
