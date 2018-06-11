<?php

namespace Drupal\threedom\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\file\FileUsage\FileUsageInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\ElementInfoManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * File upload widget
 *
 * @FieldWidget(
 *   id = "threedom_widget",
 *   label = @Translation("3D File"),
 *   field_types = {
 *     "threedom"
 *   }
 * )
 */
class ThreedomWidget extends WidgetBase implements ContainerFactoryPluginInterface {
  protected $fileMgr;
  protected $fileUsage;
  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, ElementInfoManagerInterface $element_info, EntityTypeManagerInterface $entityMgr, FileUsageInterface $fileUsage) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->elementInfo = $element_info;
    $this->fileMgr = $entityMgr->getStorage('file');
    $this->fileUsage = $fileUsage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($plugin_id, $plugin_definition, $configuration['field_definition'], $configuration['settings'], $configuration['third_party_settings'], $container->get('element_info'), $container->get('entity_type.manager'), $container->get('file.usage'));
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'progress_indicator' => 'throbber',
        'file_directory' => 'object-model',
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = [];

    $element['fid'] = [
      '#type' => 'hidden',
      '#default_value' => isset($items[$delta]->fid) ? $items[$delta]->fid : '',
      '#delta' => $delta,
      '#element_validate' => [[$this, 'validate']],
    ];
    //kint($items[$delta]);
    $element['threedom'] = [
      '#title' => $this->t('3D File'),
      '#type' => 'managed_file',
      '#default_value' => isset($items[$delta]->fid) ? $items[$delta]->fid : [],
      '#delta' => $delta,
      '#multiple' => FALSE,
      '#description' => t('Allowed extensions: obj'),
      '#upload_location' => file_default_scheme() . '://' . $this->getFieldSetting('file_directory'),
      '#upload_validators' => [
        'file_validate_extensions' => array('obj'),
      ],
    ];

    return $element;
  }


  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['progress_indicator'] = [
      '#type' => 'radios',
      '#title' => t('Progress indicator'),
      '#options' => [
        'throbber' => t('Throbber'),
        'bar' => t('Progress meter'),
      ],
      '#default_value' => $this->getSetting('progress_indicator'),
      '#description' => t('The throbber display does not show the status of uploads but takes up less space. The progress bar is helpful for monitoring progress on large uploads.'),
      '#weight' => 10,
      '#access' => file_progress_implementation(),
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = t('Progress indicator: @progress_indicator', ['@progress_indicator' => $this->getSetting('progress_indicator')]);
    return $summary;
  }

  /**
   * Validate.
   */
  public function validate($element, FormStateInterface $form_state) {
//    $delta = isset($element['#delta']) ? $element['#delta'] : -1;

    // This isn't going to work because we need the validation to be dynamic
    // But we need something like it...
    // $threedom = $form_state->getValue('field_3d_model');

    $values = $form_state->getValues();
    //$values = NestedArray::getValue($form_state->getValues(), $element['#parents']);
//    kint($form_state);

    kint($values);
    kint($element);
    $value = $element['#value'];
    if (strlen($value) == 0) {
      //$form_state->setValueForElement($element, '');
      return;
    }

   // $value = $element['#value'];

    //@todo We need to validate and save the field here...

//    $fid = (int) $threedom[$delta]['threedom']['fids'][0];
//    $file = $fid ? $this->fileMgr->load($fid) : FALSE;
//
//    if (!empty($file)) {
//      $this->fileUsage->add($file, 'threedom', 'file', $fid);
//      $form_state->setValueForElement($element, $fid);
//    }



  }

}