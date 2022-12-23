<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;

/**
 * @AdvancedQueueJobType(
 *  id = "shape_import_job",
 *  label = @Translation("ShapeImportJob"),
 *  max_retries = 3,
 *  retry_delay = 60,
 * )
 */
class ShapeImportJob extends AbstractImportJob {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job): JobResult {
    try {
      $payload = $job->getPayload();

      if (isset($payload)) {
        $fields = [
          'vid' => 'shapes',
          'name' => $payload['name'],
        ];

        $this->importEntity('taxonomy_term', $fields);

        return JobResult::success('successful');
      }
      return JobResult::failure('no payload', 3, 60);
    } catch (\Exception $e) {
      return JobResult::failure($e->getMessage());
    }
  }

}
