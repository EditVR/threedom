<?php

namespace Drupal\threedom\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Plugin\Field\FieldType\FileItem;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a field type for 3D Object Models.
 *
 * @FieldType(
 *   id = "threedom",
 *   label = @Translation("3D Object Model Field"),
 *   description = @Translation("Stores 3D Object values"),
 *   default_formatter = "threedom_formatter",
 *   default_widget = "threedom_widget",
 * )
 */
class ThreedomItem extends FieldItemBase {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    $settings = [
      'target_type'     => 'integer',
      'display_field'   => FALSE,
      'display_default' => FALSE,
      'uri_scheme'      => file_default_scheme(),
    ];
    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    $settings = [
        'file_extensions' => 'obj',
        'file_directory' => 'object-model',
        'max_filesize' => '',
        'description_field' => '',
      ];
    return $settings;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'fid' => [
          'description' => 'The ID of file entity.',
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => FALSE
        ],
        'threedom' => [
          'description' => 'A 3D Object Model Field.',
          'type' => 'varchar',
        ],
      ],
      'indexes' => [
        'fid' => ['fid'],
      ],
      'foreign keys' => [
        'target_id' => [
          'table' => 'file_managed',
          'columns' => ['fid' => 'fid'],
        ],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // $properties = parent::propertyDefinitions($field_definition);
    $properties['fid'] = DataDefinition::create('integer')
      ->setLabel(t('File ID'));
//    $properties['threedom'] = DataDefinition::create('string')
//      ->setLabel(t('3D Object Model Field'));

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = parent::storageSettingsForm($form, $form_state, $has_data);

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::fieldSettingsForm($form, $form_state);
    $settings = $this->getSettings();
    $element['file_directory'] = [
      '#type'             => 'textfield',
      '#title'            => t('File directory'),
      '#default_value'    => $settings['file_directory'],
      '#description'      => t('Optional subdirectory within the upload destination where files will be stored. Do not include preceding or trailing slashes.'),
      '#element_validate' => [[get_class($this), 'validateDirectory']],
    ];
    $element['file_extensions'] = [
      '#type'             => 'textfield',
      '#title'            => t('Allowed file extensions'),
      '#default_value'    => preg_replace('/[^a-zA-Z]+/', ' ', trim($settings['file_extensions'])),
      '#description'      => t('Separate extensions with a space or comma and do not include the leading dot.'),
      '#element_validate' => [[get_class($this), 'validateExtensions']],
      '#maxlength'        => 255,
      '#required' => TRUE,
    ];
    $element['max_filesize'] = [
      '#type' => 'textfield',
      '#title' => t('Maximum upload size'),
      '#default_value' => $settings['max_filesize'],
      '#description' => t('Enter a value like "512" (bytes), "80 KB" (kilobytes) or "50 MB" (megabytes) in order to restrict the allowed file size. If left empty the file sizes will be limited only by PHP\'s maximum post and file upload sizes (current limit <strong>%limit</strong>).', ['%limit' => format_size(file_upload_max_size())]),
      '#size' => 10,
      '#element_validate' => [[get_class($this), 'validateMaxFilesize']],
    ];

    return $element;
  }

  /**
   * Gets the entity manager.
   *
   * @return \Drupal\Core\Entity\EntityManagerInterface
   *   An entity manager.
   */
  protected function getEntityManager() {
    if (!isset($this->entityManager)) {
      $this->entityManager = \Drupal::entityManager();
    }
    return $this->entityManager;
  }

}