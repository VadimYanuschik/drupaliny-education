<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;

/**
 * @AdvancedQueueJobType(
 *  id = "shape_import_job",
 *  label = @Translation("ShapeImportJob"),
 *  max_retries = 1,
 *  retry_delay = 10,
 * )
 */
class ShapeImportJob extends AbstractImportJob {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job) {
    try {
      $payload = $job->getPayload()[0];

      if (isset($payload)) {
        $fields = [
          'vid' => 'shapes',
          'name' => $payload['name'],
        ];

        $entityID = $this->importEntity(self::STORAGE_TAXONOMY, $fields);

        if ($entityID) {
          return JobResult::success('successful');
        }
      }
      return JobResult::failure('no payload');
    } catch (\Exception $e) {
      return JobResult::failure($e->getMessage());
    }
  }

}
