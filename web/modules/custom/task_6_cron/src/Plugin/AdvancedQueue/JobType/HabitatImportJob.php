<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;

/**
 * @AdvancedQueueJobType(
 *  id = "habitat_import_job",
 *  label = @Translation("HabitatImportJob"),
 * )
 */
class HabitatImportJob extends AbstractImportJob {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job): JobResult {
    $payload = $job->getPayload();

    if (isset($payload)) {
      $fields = [
        'vid' => 'habitats',
        'name' => $payload['name'],
      ];

      try {
        $this->importEntity('taxonomy_term', $fields);
      } catch (\Exception $e) {
        return JobResult::failure($e->getMessage());
      }
      return JobResult::success('successful');
    }
    return JobResult::failure('no payload', 3, 60);
  }

}
