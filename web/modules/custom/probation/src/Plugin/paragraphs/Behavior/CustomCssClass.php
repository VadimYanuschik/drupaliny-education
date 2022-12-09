<?php

namespace Drupal\probation\Plugin\paragraphs\Behavior;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\Entity\ParagraphsType;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphsBehaviorBase;

/**
 * @ParagraphsBehavior(
 *   id = "probation_custom_css_class",
 *   label = @Translation("Custom css class"),
 *   description = @Translation("Allows to setup custom css class"),
 *   weight = 0,
 * )
 */
class CustomCssClass extends ParagraphsBehaviorBase {

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(ParagraphsType $paragraphs_type): bool {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function view(array &$build, Paragraph $paragraph, EntityViewDisplayInterface $display, $view_mode) {
    if ($css_class = $paragraph->getBehaviorSetting($this->getPluginId(), 'css_class')) {
      $build['#attributes']['class'][] = Html::getClass($css_class);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function buildBehaviorForm(ParagraphInterface $paragraph, array &$form, FormStateInterface $form_state): array {
    $form['css_class'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom css class'),
      '#description' => $this->t('Allows to setup custom css class'),
      '#default_value' => $paragraph->getBehaviorSetting($this->getPluginId(), 'css_class', ''),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary(Paragraph $paragraph): array {
    $css_class = $paragraph->getBehaviorSetting($this->getPluginId(), 'css_class');
    return [$css_class ? $this->t('Current css class: @element', ['@element' => $css_class]) : ''];
  }

}
