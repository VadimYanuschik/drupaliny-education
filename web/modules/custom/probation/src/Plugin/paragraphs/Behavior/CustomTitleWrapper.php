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
 *   id = "probation_custom_title_wrapper",
 *   label = @Translation("Custom title wrapper"),
 *   description = @Translation("Change title wrapper"),
 *   weight = 0,
 * )
 */
class CustomTitleWrapper extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {}

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $form['title_wrapper'] = [
      '#type' => 'select',
      '#options' => $this->getTitleWrappers(),
      '#title' => $this->t('Title wrapper'),
      '#description' => $this->t('Change title wrapper'),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper', 'h1'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(Paragraph $paragraph): array {
    $title_wrapper = $paragraph->getBehaviorSetting($this->getPluginId(), 'title_wrapper');
    return [$title_wrapper ? $this->t('Current title wrapper: @element', ['@element' => $title_wrapper]) : ''];
  }

  /**
   * Return available title wrapper tags
   * @return array
   */
  private function getTitleWrappers(): array {
    return [
      'h1' => $this->t('h1'),
      'h2' => $this->t('h2'),
      'h3' => $this->t('h3'),
      'h4' => $this->t('h4'),
      'h5' => $this->t('h5'),
      'h6' => $this->t('h6'),
      'div' => $this->t('div'),
    ];
  }

}
