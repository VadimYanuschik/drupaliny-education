<?php

namespace Drupal\task_6_cron\Plugin\AdvancedQueue\JobType;

use Drupal\advancedqueue\Job;
use Drupal\advancedqueue\Plugin\AdvancedQueue\JobType\JobTypeBase;

class SpecieImportJob extends JobTypeBase {

  /**
   * {@inheritdoc}
   */
  public function process(Job $job) {
  }

}
