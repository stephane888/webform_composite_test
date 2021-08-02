<?php
namespace Drupal\webform_composite_test\Plugin\WebformElement;

use Drupal\webform\Plugin\WebformElement\WebformCompositeBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\Render\Element as RenderElement;
use Drupal\webform\Plugin\WebformElementAttachmentInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformElement\WebformManagedFileBase;
use Stephane888\Debug\debugLog;

/**
 * Provides a 'webform_composite_test' element.
 *
 * @WebformElement(
 *   id = "webform_composite_test",
 *   label = @Translation("Webform example composite Test"),
 *   description = @Translation("Provides a webform element example."),
 *   category = @Translation("Custom - WBU"),
 *   multiline = TRUE,
 *   composite = TRUE,
 *   states_wrapper = TRUE,
 * )
 *
 * @see \Drupal\webform_composite_test\Element\WebformExampleComposite
 * @see \Drupal\webform\Plugin\WebformElement\WebformCompositeBase
 * @see \Drupal\webform\Plugin\WebformElementBase
 * @see \Drupal\webform\Plugin\WebformElementInterface
 * @see \Drupal\webform\Annotation\WebformElement
 */
class WebformCompositeTestPlugin extends WebformCompositeBase {

	/**
	 *
	 * {@inheritdoc}
	 */
	public function formatHtml(array $element, WebformSubmissionInterface $webform_submission, array $options = []) {
		// dump($webform_submission->id());
		$submission = [];
		$submission = $this->getValue($element["#webform_composite_elements"], $webform_submission, $options);
		// dump($submission);
		// dump($element['#webform_composite_elements']);
		$output = [];
		$generatesummay = \Drupal::service('webform_composite_test.generatesummay');
		if ($submission) {
			$generatesummay->submission = $submission;
			$generatesummay->getTemplatesPrices($element, $output);
		}
		// dump($output);
		return $output;
	}

	/**
	 * Get initialized composite element.
	 * // cette fonction ne retourne pas les elments en profondeur.or dans ce module, on a [medias][piece-photos-1] avec media piece-photos-1 qui est le $composite_key.
	 *
	 * @param array $element
	 *        	A composite element.
	 * @param string $composite_key
	 *        	(Optional) Composite sub element key.
	 *        	
	 * @return array The initialized composite element or a specific composite sub element key.
	 *        
	 * @see \Drupal\webform\Plugin\WebformElement\WebformCompositeBase::initialize
	 */
	public function getInitializedCompositeElement(array $element, $composite_key = NULL) {
		$composite_elements = $element['#webform_composite_elements'];
		// dump($composite_elements);
		if (isset($composite_key)) {
			// return (isset($composite_elements[$composite_key])) ? $composite_elements[$composite_key] : NULL;
			return $this->findComposites($composite_elements, $composite_key);
		}
		else {
			return $composite_elements;
		}
	}

	/**
	 * recherche l'element composite.
	 *
	 * @param array $composite_elements
	 */
	private function findComposites(array $composite_elements, $composite_key) {
		$composite_element = null;
		if (isset($composite_elements[$composite_key])) {
			$composite_element = $composite_elements[$composite_key];
		}
		else {
			foreach ($composite_elements as $composite) {
				if (is_array($composite) && ! empty($composite["#type"])) {
					$composite_element = $this->findComposites($composite, $composite_key);
				}
			}
		}
		return $composite_element;
	}

	/**
	 *
	 * {@inheritdoc}
	 */
	public function postSave(array &$element, WebformSubmissionInterface $webform_submission, $update = TRUE) {
		$webform = $webform_submission->getWebform();
		if ($webform->isResultsDisabled() || ! $this->hasManagedFiles($element)) {
			return;
		}

		$original_data = $webform_submission->getOriginalData();
		$data = $webform_submission->getData();

		$composite_elements_managed_files = $this->getManagedFiles($element);
		foreach ($composite_elements_managed_files as $composite_key) {
			$original_fids = $this->getManagedFileIdsFromData($element, $original_data, $composite_key);
			$fids = $this->getManagedFileIdsFromData($element, $data, $composite_key);

			// Delete the old file uploads.
			$delete_fids = array_diff($original_fids, $fids);
			WebformManagedFileBase::deleteFiles($webform_submission, $delete_fids);

			// Add new files.
			if ($fids) {
				$composite_element = $this->getInitializedCompositeElement($element, $composite_key);

				// debugLog::$themeName = null;
				// debugLog::kintDebugDrupal([
				// $composite_element,
				// $composite_key,
				// $element
				// ], 'WebformCompositeTestPlugin__postSave', null, true);
				// dump($composite_key);
				/** @var \Drupal\webform\Plugin\WebformElement\WebformManagedFileBase $composite_element_plugin */
				$composite_element_plugin = $this->elementManager->getElementInstance($composite_element);
				$composite_element_plugin->addFiles($composite_element, $webform_submission, $fids);
			}
		}
	}

	/**
	 * Build the composite elements settings table.
	 *
	 * @param array $form
	 *        	An associative array containing the structure of the form.
	 * @param \Drupal\Core\Form\FormStateInterface $form_state
	 *        	The current state of the form.
	 *        	
	 * @return array A renderable array container the composite elements settings table.
	 */
	protected function buildCompositeElementsTable(array $form, FormStateInterface $form_state) {
		$labels_help = [
			'help' => [
				'#type' => 'webform_help',
				'#help' => '<b>' . $this->t('Key') . ':</b> ' . $this->t('The machine-readable name.') . '<hr/><b>' . $this->t('Title') . ':</b> ' . $this->t('This is used as a descriptive label when displaying this webform element.') . '<hr/><b>' . $this->t('Placeholder') . ':</b> ' . $this->t('The placeholder will be shown in the element until the user starts entering a value.') . '<hr/><b>' . $this->t('Description') . ':</b> ' . $this->t('A short description of the element used as help for the user when they use the webform.') . '<hr/><b>' . $this->t('Help text') . ':</b> ' . $this->t('A tooltip displayed after the title.') . '<hr/><b>' . $this->t('Title display') . ':</b> ' . $this->t('A tooltip displayed after the title.'),
				'#help_title' => $this->t('Labels')
			]
		];
		$settings_help = [
			'help' => [
				'#type' => 'webform_help',
				'#help' => '<b>' . $this->t('Required') . ':</b> ' . $this->t('Check this option if the user must enter a value.') . '<hr/><b>' . $this->t('Type') . ':</b> ' . $this->t('The type of element to be displayed.') . '<hr/><b>' . $this->t('Options') . ':</b> ' . $this->t('Please select predefined options.'),
				'#help_title' => $this->t('Settings')
			]
		];

		$header = [
			'visible' => $this->t('Visible'),
			'labels' => [
				'data' => [
					[
						'title' => [
							'#markup' => $this->t('Labels')
						]
					],
					$labels_help
				]
			],
			'settings' => [
				'data' => [
					[
						'title' => [
							'#markup' => $this->t('Settings')
						]
					],
					$settings_help
				]
			]
		];

		$rows = [];
		$composite_elements = $this->getCompositeElements();
		foreach ($composite_elements as $composite_key => $composite_element) {
			$title = (isset($composite_element['#title'])) ? $composite_element['#title'] : $composite_key;
			$type = isset($composite_element['#type']) ? $composite_element['#type'] : NULL;
			$t_args = [
				'@title' => $title
			];
			$state_disabled = [
				'disabled' => [
					':input[name="properties[' . $composite_key . '__access]"]' => [
						'checked' => FALSE
					]
				]
			];

			$row = [];

			// Access.
			$row[$composite_key . '__access'] = [
				'#title' => $this->t('@title visible', $t_args),
				'#title_display' => 'invisible',
				'#type' => 'checkbox',
				'#return_value' => TRUE
			];

			// Key, title, placeholder, help, description, and title display.
			if ($type) {
				$row['labels'] = [
					'data' => [
						$composite_key . '__key' => [
							'#markup' => $composite_key,
							'#suffix' => '<hr/>',
							'#access' => TRUE
						],
						$composite_key . '__title' => [
							'#type' => 'textfield',
							'#title' => $this->t('@title title', $t_args),
							'#title_display' => 'invisible',
							'#description' => $this->t('This is used as a descriptive label when displaying this webform element.'),
							'#description_display' => 'invisible',
							'#placeholder' => $this->t('Enter title…'),
							'#required' => TRUE,
							'#states' => $state_disabled
						],
						$composite_key . '__placeholder' => [
							'#type' => 'textfield',
							'#title' => $this->t('@title placeholder', $t_args),
							'#title_display' => 'invisible',
							'#description' => $this->t('The placeholder will be shown in the element until the user starts entering a value.'),
							'#description_display' => 'invisible',
							'#placeholder' => $this->t('Enter placeholder…'),
							'#states' => $state_disabled
						],
						$composite_key . '__help' => [
							'#type' => 'textarea',
							'#title' => $this->t('@title help text', $t_args),
							'#title_display' => 'invisible',
							'#description' => $this->t('A short description of the element used as help for the user when they use the webform.'),
							'#description_display' => 'invisible',
							'#rows' => 2,
							'#placeholder' => $this->t('Enter help text…'),
							'#states' => $state_disabled
						],
						$composite_key . '__description' => [
							'#type' => 'textarea',
							'#title' => $this->t('@title description', $t_args),
							'#title_display' => 'invisible',
							'#description' => $this->t('A tooltip displayed after the title.'),
							'#description_display' => 'invisible',
							'#rows' => 2,
							'#placeholder' => $this->t('Enter description…'),
							'#states' => $state_disabled
						],
						$composite_key . '__title_display' => [
							'#type' => 'select',
							'#title' => $this->t('@title title display', $t_args),
							'#title_display' => 'invisible',
							'#description' => $this->t('A tooltip displayed after the title.'),
							'#description_display' => 'invisible',
							'#options' => [
								'before' => $this->t('Before'),
								'after' => $this->t('After'),
								'inline' => $this->t('Inline'),
								'invisible' => $this->t('Invisible')
							],
							'#empty_option' => $this->t('Select title display… '),
							'#states' => $state_disabled
						]
					]
				];
			}
			else {
				$row['title_and_description'] = [
					'data' => [
						''
					]
				];
			}

			// Type and options.
			// Using if/else instead of switch/case because of complex conditions.
			$row['settings'] = [];

			// Required.
			if ($type) {
				$row['settings']['data'][$composite_key . '__required'] = [
					'#type' => 'checkbox',
					'#title' => $this->t('Required'),
					'#description' => $this->t('Check this option if the user must enter a value.'),
					'#description_display' => 'invisible',
					'#return_value' => TRUE,
					'#states' => $state_disabled,
					'#wrapper_attributes' => [
						'style' => 'white-space: nowrap'
					],
					'#suffix' => '<hr/>'
				];
			}

			if ($type === 'tel') {
				$row['settings']['data'][$composite_key . '__type'] = [
					'#type' => 'select',
					'#title' => $this->t('@title type', $t_args),
					'#title_display' => 'invisible',
					'#description' => $this->t('The type of element to be displayed.'),
					'#description_display' => 'invisible',
					'#required' => TRUE,
					'#options' => [
						'tel' => $this->t('Telephone'),
						'textfield' => $this->t('Text field')
					],
					'#states' => $state_disabled
				];
			}
			elseif (in_array($type, [
				'select',
				'webform_select_other',
				'radios',
				'webform_radios_other'
			])) {
				// Get base type (select or radios).
				$base_type = preg_replace('/webform_(select|radios)_other/', '\1', $type);

				// Get type options.
				switch ($base_type) {
					case 'radios':
						$settings = [
							'radios' => $this->t('Radios'),
							'webform_radios_other' => $this->t('Radios other'),
							'textfield' => $this->t('Text field')
						];
						break;

					case 'select':
					default:
						$settings = [
							'select' => $this->t('Select'),
							'webform_select_other' => $this->t('Select other'),
							'textfield' => $this->t('Text field')
						];
						break;
				}

				$row['settings']['data'][$composite_key . '__type'] = [
					'#type' => 'select',
					'#title' => $this->t('@title type', $t_args),
					'#title_display' => 'invisible',
					'#description' => $this->t('The type of element to be displayed.'),
					'#description_display' => 'invisible',
					'#required' => TRUE,
					'#options' => $settings,
					'#states' => $state_disabled
				];

				$composite_options = $this->getCompositeElementOptions($composite_key);

				// Make sure custom options defined via the YAML source are not
				// deleted when a composite element is edited via the UI.
				/** @var \Drupal\webform_ui\Form\WebformUiElementEditForm $form_object */
				$form_object = $form_state->getFormObject();
				$element = $form_object->getElement();
				$composite_options_default_value = (isset($element['#' . $composite_key . '__options'])) ? $element['#' . $composite_key . '__options'] : NULL;
				if ($composite_options_default_value && (is_array($composite_options_default_value) || ! isset($composite_options[$composite_options_default_value]))) {
					$webform = $form_object->getWebform();
					if ($this->currentUser->hasPermission('edit webform source') && $webform->hasLinkTemplate('source-form')) {
						$t_args = [
							':href' => $webform->toUrl('source-form')->toString()
						];
						$message = $this->t('Custom options can only be updated via the <a href=":href">YAML source</a>.', $t_args);
					}
					else {
						$message = $this->t('Custom options can only be updated via the YAML source.');
					}
					$row['settings']['data'][$composite_key . '__options'] = [
						'#type' => 'value',
						'#suffix' => '<em>' . $message . '</em>'
					];
				}
				elseif ($composite_options) {
					$row['settings']['data'][$composite_key . '__options'] = [
						'#type' => 'select',
						'#title' => $this->t('@title options', $t_args),
						'#title_display' => 'invisible',
						'#description' => $this->t('Please select predefined options.'),
						'#description_display' => 'invisible',
						'#options' => $composite_options,
						'#required' => TRUE,
						'#attributes' => [
							'style' => 'width: 100%;'
						],
						'#states' => $state_disabled + [
							'invisible' => [
								':input[name="properties[' . $composite_key . '__type]"]' => [
									'value' => 'textfield'
								]
							]
						]
					];
				}
				else {
					$row['settings']['data'][$composite_key . '__options'] = [
						'#type' => 'value'
					];
				}
			}
			else {
				$row['settings']['data'][$composite_key . '__type'] = [
					'#type' => 'textfield',
					'#access' => FALSE
				];
				$row['settings']['data']['markup'] = [
					'#type' => 'container',
					'#markup' => $this->elementManager->getElementInstance($composite_element)->getPluginLabel(),
					'#access' => TRUE
				];
			}

			$rows[$composite_key] = $row;
		}

		return [
			'#type' => 'table',
			'#header' => $header
		] + $rows;
	}
}
