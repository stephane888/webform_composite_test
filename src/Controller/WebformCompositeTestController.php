<?php

namespace Drupal\webform_composite_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Webform Composite test routes.
 */
class WebformCompositeTestController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
