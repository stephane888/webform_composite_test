<?php
namespace Drupal\webform_composite_test\Services;

USE Drupal\file\Entity\File;

class GenerateSummay {

	protected $ListPrice;

	public $submission = [];

	private $currentSubmission = [];

	private $totalPrice = 0;

	private $showEmpty = true;

	const minPrice = 240;

	function __construct(ListPrice $ListPrice) {
		$this->ListPrice = $ListPrice;
	}

	public function getTemplates(array $elements, &$output) {
		// $this->showEmpty = false;
		if ($this->submission)
			$this->generateTemplate2($elements, $output);
	}

	/**
	 * Contruit un tableau.
	 *
	 * @param array $elements
	 * @param array $output
	 */
	public function getTemplatesTablesPrices(array $elements, array &$output) {
		// dump($elements);
		if (! empty($elements["#webform_composite_elements"])) {
			$elements = $elements["#webform_composite_elements"];
			// $output = [
			// '#type' => 'table',
			// '#header' => $headers,
			// '#rows' => $rows
			// // '#empty' => $this->t('No content has been found.')
			// ];
			$this->generateTable($elements, $output);
		}
	}

	private function generateTable(array $elements, array &$output) {
		$this->showEmpty = false;
		$output = [
			'#type' => 'details',
			'#open' => false,
			'#title' => 'Devis',
			'#attributes' => [
				'class' => [
					'table-container'
				]
			],
			'#value' => [],
			'#summary_attributes' => []
		];
		foreach ($this->submission as $submission) {
			// .
			$this->currentSubmission = $submission;
			// dump($this->currentSubmission);
			foreach ($elements as $key => $element) {
				if (is_array($element) && ! empty($element['#type'])) {
					if ($key == 'piece') {
						// On importe la piece.
						$output['#value']['piece'] = [];
						$this->templateSelect($output['#value']['piece'], 'piece', $element, $this->currentSubmission);
					}
					else {
						// On importe les infos de la piece selectionnée.
						$output['#value'][$this->currentSubmission['piece']] = [];
						if (! empty($elements[$this->currentSubmission['piece']]))
							$this->generateTableElments([
								$elements[$this->currentSubmission['piece']]
							], $output['#value'][$this->currentSubmission['piece']]);
					}
				}
			}
			$this->addPrice($output['#value'], false);
			$output['#title'] .= ' ' . $this->totalPrice . ' €';
		}
	}

	/**
	 * Return le rendu avec le prix dessus.
	 *
	 * @param array $elements
	 * @param array $output
	 */
	public function getTemplatesPrices(array $elements, &$output) {
		$this->showEmpty = false;
		if (! empty($elements["#webform_composite_elements"])) {
			$elements = $elements["#webform_composite_elements"];
			$this->generateTemplate2($elements, $output);
			$this->addPrice($output);
		}
	}

	private function addPrice(array &$output, $description = true) {
		if ($this->totalPrice) {
			$info = [];
			if (self::minPrice >= $this->totalPrice)
				$this->totalPrice = self::minPrice;
			$output['totat-lprice'] = [
				'#type' => 'html_tag',
				'#tag' => 'p',
				'#attributes' => [
					'class' => [
						'montant-total'
					]
				],
				'#value' => 'Montant total de la prestation : ' . $this->totalPrice . ' €'
			];
			if ($description)
				$info['contenttext'] = [
					[
						'#type' => 'html_tag',
						'#tag' => 'p',
						'#attributes' => [
							'class' => [
								'text-description'
							]
						],
						'#value' => " Le tarif comprend : Le déplacement, la main d'oeuvre,
							 le nettoyage du chantier, peinture de marque professionnelle type Seigneurie ou similaire,
							 la colle et le ragreage si necessaire pour les revetements au sol, circuit d'alimentaiton en cuivre pour la plomberie,
							 les prises et equipements electrique de marque LEGRAND ou equivalent.
							"
					],
					[
						'#type' => 'html_tag',
						'#tag' => 'p',
						'#attributes' => [
							'class' => [
								'text-description'
							]
						],
						'#value' => 'Le tarif ne comprend pas : Les revetements au sol(carrelage, parquet...), la robineterie...'
					]
				];
			if (! empty($info))
				$output += $info;
		}
	}

	/**
	 *
	 * @param array $elements
	 * @param array $build
	 */
	private function generateTemplate2($elements, &$build) {
		// dump($elements);
		foreach ($this->submission as $key => $submission) {
			// dump($submission);
			$output = [
				'#type' => 'html_tag',
				'#tag' => 'div',
				'#attributes' => [
					'class' => [
						'primary-container'
					]
				],
				'#value' => ''
			];
			$this->currentSubmission = $submission;
			// On genere le template suivant la valeur qui a été selectionner. ( on pourra utiliser afterbuild pour une utilisation globale)
			if (! empty($this->currentSubmission['piece'])) {
				// on importe la piece
				$output['piece'] = [];
				$this->templateSelect($output['piece'], 'piece', $elements['piece'], $this->currentSubmission);
				// On importe les infos de la piece selectionnée.
				$output[$this->currentSubmission['piece']] = [];
				if (! empty($elements[$this->currentSubmission['piece']]))
					$this->generateTemplate([
						$elements[$this->currentSubmission['piece']]
					], $output[$this->currentSubmission['piece']]);
				// on importe les travaux.
				$output['travaux'] = [];
				$this->generateTemplate([
					$elements['travaux']
				], $output['travaux']);
				// on ajoute le blocs medias
				$output['medias'] = [];
				$this->generateTemplate([
					$elements['medias']
				], $output['medias']);
				$build[$key] = $output;
			}
		}
		// dump($build);
	}

	/**
	 * --
	 */
	private function generateTableElments(array $elements, &$output) {
		foreach ($elements as $key => $element) {
			if (is_array($element)) {
				if (! empty($element['#type'])) {
					if ($element['#type'] == 'details') {
						$this->generateTableElmentDetails($element, $output);
					}
					elseif ($element['#type'] == 'html_tag') {
						$subOutput = [];
						$this->TemplateHtmlTag($subOutput, $element);
						if (! empty($subOutput)) {
							$output[$key] = $subOutput;
						}
					}
					elseif ($element['#type'] == 'radios' || $element['#type'] == 'select' || $element['#type'] == 'checkbox' || $element['#type'] == 'textfield' || $element['#type'] == 'number') {
						/**
						 * permet d'eliminer les champs vides;
						 *
						 * @var array $subOutput
						 */
						$subOutput = [];
						$this->templateSelect($subOutput, $key, $element, $this->currentSubmission);
						if (! empty($subOutput)) {
							$output[$key] = $subOutput;
						}
					}
					elseif ($element['#type'] == 'managed_file') {
						$subOutput = [];
						$this->templateFiles($subOutput, $key, $element, $this->currentSubmission);
						if (! empty($subOutput)) {
							$output[$key] = $subOutput;
						}
					}
					else {
						\Drupal::messenger()->addWarning(' Template non definit : ' . json_encode($element['#type']));
					}
				}
			}
		}
	}

	private function generateTableElmentDetails(array $element, array &$output) {
		// foreach ($element as $key => $value) {
		// if (is_array($value)) {
		// $output[$key] = [];
		// if (isset($value['#type'])) {
		// dump($value['#type']);
		// $value = [
		// $value
		// ];
		// }
		// $this->generateTableElments($value, $output[$key]);
		// }
		// }

		//
		$output['container-details_titre'] = [
			'#type' => 'html_tag',
			'#tag' => 'h5',
			'#attributes' => [
				'class' => [
					'container-details_titre'
				]
			],
			'#value' => $element['#title']
		];
		//
		$output['#type'] = 'html_tag';
		$output['#attributes'] = [
			'class' => [
				'container-details'
			]
		];
		$output['#value'] = '';
		$output['#tag'] = 'div';
		//
		$key = 'container-details_body';
		$this->generateTableElments($element, $output[$key]);
	}

	private function generateTemplate($elements, &$output) {
		$submission = $this->currentSubmission;
		foreach ($elements as $key => $element) {
			if (is_array($element) && ! empty($element['#type'])) {
				if ($element['#type'] == 'details') {
					$subOutput = [];
					$this->TemplateDetails($subOutput, $element);
					if (! empty($subOutput)) {
						$output[$key] = $subOutput;
					}
				}
				elseif ($element['#type'] == 'html_tag') {
					$subOutput = [];
					$this->TemplateHtmlTag($subOutput, $element);
					if (! empty($subOutput)) {
						$output[$key] = $subOutput;
					}
				}
				elseif ($element['#type'] == 'radios' || $element['#type'] == 'select' || $element['#type'] == 'checkbox' || $element['#type'] == 'textfield' || $element['#type'] == 'number') {
					/**
					 * permet d'eliminer les champs vides;
					 *
					 * @var array $subOutput
					 */
					$subOutput = [];
					$this->templateSelect($subOutput, $key, $element, $submission);
					if (! empty($subOutput)) {
						$output[$key] = $subOutput;
					}
				}
				elseif ($element['#type'] == 'managed_file') {
					$subOutput = [];
					$this->templateFiles($subOutput, $key, $element, $submission);
					if (! empty($subOutput)) {
						$output[$key] = $subOutput;
					}
				}
				// elseif ($element['#type'] == 'radios' || $element['#type'] == 'checkbox' || $element['#type'] == 'textfield') {
				// /**
				// * permet d'eliminer les champs vides;
				// *
				// * @var array $subOutput
				// */
				// $subOutput = [];
				// $this->templateOrther($subOutput, $key, $element, $submission);
				// if (! empty($subOutput)) {
				// $output[$key] = $subOutput;
				// }
				// }
				else {
					\Drupal::messenger()->addWarning(' Template non definit : ' . json_encode($element['#type']));
				}
			}
		}
	}

	/**
	 *
	 * @param array $output
	 * @param string $key
	 * @param array $element
	 */
	private function templateSelect(array &$output, $key, array $element, $submission) {
		$submissionValue = isset($submission[$key]) ? $submission[$key] : null;
		// dump($submissionValue);
		if (! $this->showEmpty && empty($submission[$key])) {
			// unset($output[$key]);
			return '';
		}
		if (! $this->showEmpty && $price = $this->ListPrice->getPrice($key, $submission)) {
			$this->totalPrice += $price;
		}
		$output['span'] = [
			[
				'#type' => 'html_tag',
				'#tag' => 'span',
				'#value' => $element["#title"]
			],
			[
				'#type' => 'html_tag',
				'#tag' => 'span',
				'#value' => ' : ' . $this->getLabelValue($submissionValue, $element)
			]
		];
		// $output['#value'] = $texte;
		$output['#type'] = 'html_tag';
		$output['#attributes'] = [
			'class' => [
				$key,
				'ligne-item'
			]
		];
		$output['#tag'] = 'div';
	}

	private function templateFiles(array &$output, $key, array $element, $submission) {
		$fid = ! empty($submission[$key]) ? $submission[$key] : null;
		if ($fid) {
			$file = File::load($fid);
			if (! empty($file)) {
				$path = $file->url();
				$output['span'] = [
					[
						'#type' => 'html_tag',
						'#tag' => 'a',
						'#value' => ' Télecharger ',
						'#attributes' => [
							'href' => $path,
							'download' => $file->getFilename()
						]
					],
					[
						'#type' => 'html_tag',
						'#tag' => 'span',
						'#value' => ' || ',
						'#attributes' => []
					],
					[
						'#type' => 'html_tag',
						'#tag' => 'a',
						'#value' => ' Voir : ' . $file->getFilename(),
						'#attributes' => [
							'href' => $path,
							'target' => '_blank'
						]
					]
				];
				$output['#type'] = 'html_tag';
				$output['#attributes'] = [
					'class' => [
						$key,
						'ligne-item'
					]
				];
				$output['#tag'] = 'div';
			}
		}
	}

	private function getLabelValue($submissionValue, array $element) {
		// Cas radios
		if (($element['#type'] == 'radios') && isset($element['#options'][$submissionValue])) {
			return $element['#options'][$submissionValue];
		}
		elseif (($element['#type'] == 'select') && isset($element['#options'][$submissionValue])) {
			return $element['#options'][$submissionValue];
		}
		elseif ($element['#type'] == 'checkbox') {
			return $submissionValue ? 'oui' : 'non';
		}
		else
			return $submissionValue;
	}

	private function TemplateDetails(array &$output, $element) {
		$output['#value'] = [];
		$this->generateTemplate($element, $output['#value']);
		if (! $this->showEmpty && empty($output['#value'])) {
			unset($output['#value']);
			return '';
		}
		$output['#theme'] = $element['#type'];
		$output['#title'] = $element['#title'];
		$output['#open'] = false;
		$output['#attributes'] = [];
		$output['#summary_attributes'] = [];
	}

	private function TemplateHtmlTag(array &$output, $element) {
		$this->generateTemplate($element, $output);
		if (! $this->showEmpty && empty($output)) {
			return '';
		}
		$output['#type'] = 'html_tag';
		$output['#tag'] = $element['#tag'];
		if (isset($element['#value']))
			$output['#value'] = $element['#value'];
		//
		// dump($element);
	}

	private function getHeader(array &$lines) {
		if (isset($lines['#webform_id'])) {
			$webform_id = $lines['#webform_id'];
		}
		elseif (isset($lines['#webform_composite_id'])) {
			$webform_id = $lines['#webform_composite_id'];
		}
		else
			$webform_id = 'webform_id' . rand(99, 999);
		$lines[$webform_id] = [
			'#type' => 'html_tag',
			'#tag' => 'h4',
			'#value' => $lines["#title"]
		];
	}
}