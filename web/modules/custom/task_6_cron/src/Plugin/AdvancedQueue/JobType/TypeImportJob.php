<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\Plugin\AdvancedQueue\JobType\JobTypeBase;

/**
 * @AdvancedQueueJobType(
 *  id = "type_import_job",
 *  label = @Translation("TypeImportJob"),
 *  max_retries = 1,
 *  retry_delay = 10,
 * )
 */
class TypeImportJob extends JobTypeBase {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job) {
  }

}
