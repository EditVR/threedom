<?php

namespace Drupal\threedom\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'threedom_default' formatter.
 *
 * @FieldFormatter(
 *   id = "threedom_formatter",
 *   label = @Translation("3D Object Model formatter"),
 *   field_types = {
 *     "threedom"
 *   }
 * )
 */
class ThreedomFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    foreach ($items as $item) {

    }

    // Out return here needs to be a fully rendered model.
    // We will need to make sure we add the JS lib to this.
    return [
      '#type'     => 'markup',
      '#data'     => 'Data here',
    ];

  }
}
