<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;

/**
 * @AdvancedQueueJobType(
 *  id = "type_import_job",
 *  label = @Translation("TypeImportJob"),
 *  max_retries = 3,
 *  retry_delay = 60,
 * )
 */
class TypeImportJob extends AbstractImportJob {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job): JobResult {
    try {
      $payload = $job->getPayload();

      if (isset($payload)) {
        $fields = [
          'vid' => 'types',
          'name' => $payload['name'],
        ];

        $this->importEntity(self::STORAGE_TAXONOMY, $fields);

        return JobResult::success('successful');
      }
      return JobResult::failure('no payload', 3, 60);
    } catch (\Exception $e) {
      return JobResult::failure($e->getMessage());
    }
  }

}
