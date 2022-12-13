<?php

/**
 * Implements hook_preprocess_HOOK().
 */
function odd_even_minute_preprocess_odd_even_minute_template(&$variables): void {
  $variables['#attached']['library'][] = 'odd_even_minute/odd_even_minute_styling';
}
