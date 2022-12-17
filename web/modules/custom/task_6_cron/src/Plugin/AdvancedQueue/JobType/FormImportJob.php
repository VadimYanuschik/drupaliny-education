<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\JobResult;

/**
 * @AdvancedQueueJobType(
 *  id = "form_import_job",
 *  label = @Translation("FormImportJob"),
 *  max_retries = 1,
 *  retry_delay = 10,
 * )
 */
class FormImportJob extends AbstractImportJob {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job) {
    try {
      $payload = $job->getPayload()[0];

      if (isset($payload)) {
        $fields = [
          'vid' => 'forms',
          'name' => $payload['name'],
        ];

        return $this->importEntity(self::STORAGE_TAXONOMY, $fields);
      }
      return JobResult::failure('no payload');
    } catch (\Exception $e) {
      return JobResult::failure($e->getMessage());
    }
  }

}
