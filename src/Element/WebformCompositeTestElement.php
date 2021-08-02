<?php
namespace Drupal\webform_composite_test\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Element\WebformCompositeBase;
use Drupal\webform_composite_test\Services\Cuisine;
use Drupal\Component\Plugin\PluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\webform\WebformSubmissionConditionsValidator;

/**
 * Provides a 'webform_composite_test'.
 *
 * Webform composites contain a group of sub-elements.
 *
 *
 * IMPORTANT:
 * Webform composite can not contain multiple value elements (i.e. checkboxes)
 * or composites (i.e. webform_address)
 *
 * @FormElement("webform_composite_test")
 *
 * @see \Drupal\webform\Element\WebformCompositeBase
 * @see \Drupal\webform_composite_test\Element\WebformExampleComposite
 */
class WebformCompositeTestElement extends WebformCompositeBase {

  // protected $FormCuisine;

  // function __construct(Cuisine $FormCuisine)
  // {
  // $this->FormCuisine = $FormCuisine;
  // }

  /**
   *
   * {@inheritdoc}
   */
  // public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  // {
  // return new static($configuration, $plugin_id, $plugin_definition, $container->getParameter('webform_composite_test.cuisine'));
  // }

  /**
   *
   * {@inheritdoc}
   */
  public function getInfo()
  {
    return parent::getInfo() + [
      '#theme' => 'webform_composite_test'
    ];
  }

  /**
   *
   * {@inheritdoc}
   */
  public static function getCompositeElements(array $element)
  {
    $FormPieces = \Drupal::service('webform_composite_test.pieces');
    $FormPieces->form($element);
    return $element;
  }
}