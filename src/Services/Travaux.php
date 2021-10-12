<?php
namespace Drupal\webform_composite_test\Services;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform_composite_test\Services\Travaux\Renovation;
use Drupal\webform_composite_test\Services\Travaux\Cloisons;
use Drupal\webform_composite_test\Services\Travaux\Revetement;
use Drupal\webform_composite_test\Services\Travaux\Electricite;

class Travaux {

	protected $Renovation;

	protected $Cloisons;

	protected $Revetement;

	protected $Electricite;

	function __construct(Renovation $Renovation, Cloisons $Cloisons, Revetement $Revetement, Electricite $Electricite) {
		$this->Renovation = $Renovation;
		$this->Cloisons = $Cloisons;
		$this->Revetement = $Revetement;
		$this->Electricite = $Electricite;
	}

	public function form(array &$elements) {
		$elements['travaux'] = array(
			'#type' => 'details',
			'#title' => 'Travaux effectués dans la piece',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => FALSE,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildTravaux'
				]
			],
			'#attributes' => [
				'class' => [
					'main-details'
				]
			]
		);

		$elements['travaux']['selec'] = [
			'#type' => 'html_tag',
			'#tag' => 'div'
			// '#value' => 'Quels équipements souhaitez-vous ?'
		];

		$elements['travaux']['selec']['renovation'] = [
			'#type' => 'checkbox',
			'#title' => t('Rénovation Murs / Plafonds'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			]
		];

		$elements['travaux']['selec']['cloisons'] = [
			'#type' => 'checkbox',
			'#title' => t('Création Cloisons / Plafonds'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			]
		];

		$elements['travaux']['selec']['revetement'] = [
			'#type' => 'checkbox',
			'#title' => t('Revetement au sol'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			]
		];

		$elements['travaux']['selec']['electricite'] = [
			'#type' => 'checkbox',
			'#title' => t('Electricite'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			]
		];
		$elements['travaux']['selec']['reno'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => ''
		];
		$this->Renovation->form($elements['travaux']['selec']['reno']);
		//
		$elements['travaux']['selec']['cloons'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => ''
		];
		$this->Cloisons->form($elements['travaux']['selec']['cloons']);
		//
		$elements['travaux']['selec']['rv-sol'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => ''
		];
		$this->Revetement->form($elements['travaux']['selec']['rv-sol']);
		//
		$elements['travaux']['selec']['elect'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => ''
		];
		$this->Electricite->form($elements['travaux']['selec']['elect']);
	}

	static function afterBuildTravaux(array $element, FormStateInterface $form_state) {
		$composite_name = '';
		if (! empty($element["#parents"]) && count($element["#parents"]) > 2) {
			for ($i = 0; $i < (count($element["#parents"]) - 1); $i ++) {
				if ($i == 0)
					$composite_name .= $element["#parents"][$i];
				elseif ($i > 0)
					$composite_name .= '[' . $element["#parents"][$i] . ']';
			}
		}
		// dump($composite_name);
		$element['#states']['visible'] = [
			':input[name="' . $composite_name . '[piece]"]' => [
				[
					'value' => 'cuisine'
				],
				[
					'or'
				],
				[
					'value' => 'sdb'
				],
				[
					'or'
				],
				[
					'value' => 'salon'
				],
				[
					'or'
				],
				[
					'value' => 'chambre'
				],
				[
					'or'
				],
				[
					'value' => 'salle_a_manger'
				],
				[
					'or'
				],
				[
					'value' => 'couloir'
				],
				[
					'or'
				],
				[
					'value' => 'bde'
				],
				[
					'or'
				],
				[
					'value' => 'tle'
				],
				[
					'or'
				],
				[
					'value' => 'dressing'
				],
				[
					'or'
				],
				[
					'value' => 'bureau'
				]
			]
		];
		return $element;
	}
}