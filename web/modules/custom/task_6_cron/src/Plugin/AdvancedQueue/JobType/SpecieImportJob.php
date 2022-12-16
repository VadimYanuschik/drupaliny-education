<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\Plugin\AdvancedQueue\JobType\JobTypeBase;

/**
 * @AdvancedQueueJobType(
 *  id = "specie_import_job",
 *  label = @Translation("SpecieImportJob"),
 *  max_retries = 1,
 *  retry_delay = 10,
 * )
 */
class SpecieImportJob extends JobTypeBase {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job) {
  }

}
