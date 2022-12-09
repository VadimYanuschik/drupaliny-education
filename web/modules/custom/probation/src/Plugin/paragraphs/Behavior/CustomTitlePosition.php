<?php

namespace Drupal\probation\Plugin\paragraphs\Behavior;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * @ParagraphsBehavior(
 *   id = "probation_custom_title_position",
 *   label = @Translation("Custom title position"),
 *   description = @Translation("Allows to setup custom title position"),
 *   weight = 0,
 * )
 */
class CustomTitlePosition extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type): bool {
    return TRUE;
  }

  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    if ($paragraph->getBehaviorSetting($this->getPluginId(), 'is_bolded')) {
      $build['#attributes']['class'][] = 'text-bold';
    }

    if ($paragraph->getBehaviorSetting($this->getPluginId(), 'is_centered')) {
      $build['#attributes']['class'][] = 'text-align-center';
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $form['is_centered'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Move title to center'),
      '#description' => $this->t('Make your title in centered position'),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'is_centered', FALSE),
    ];

    $form['is_bolded'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Make title bold'),
      '#description' => $this->t('Make your title bold'),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'is_bolded', FALSE),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(Paragraph $paragraph): array {
    $is_centered = $paragraph->getBehaviorSetting($this->getPluginId(), 'is_centered');
    $is_bolded = $paragraph->getBehaviorSetting($this->getPluginId(), 'is_bolded');

    $summary[] = $this->t('Title centered: @value', ['@value' => $is_centered]);
    $summary[] = $this->t('Title bold: @value', ['@value' => $is_bolded]);

    return $summary;
  }

}
