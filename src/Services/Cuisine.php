<?php
namespace Drupal\webform_composite_test\Services;

use Drupal\Core\Form\FormStateInterface;

class Cuisine {

	public function form(array &$elements) {
		$elements['cuisine'] = array(
			'#type' => 'details',
			'#title' => 'RACCORDEMENTS PLOMBERIE ET DEPOSE CUISINE',
			// '#description' => t('Lorem ipsum.'),
			'#open' => FALSE,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCuisine'
				]
			],
			'#attributes' => [
				'class' => [
					'main-details'
				]
			],
			"#webform_id" => 'id-cuisine',
			'#webform_key' => 'cuisine'
		);
		$elements['cuisine']['demonter'] = [
			'#type' => 'radios',
			'#title' => t('Faut-il démonter une cuisine existante ?'),
			// '#value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCuisineAction'
				]
			]
		];

		$elements['cuisine']['raccordements'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous ajouter les raccordements de plomberie de la cuisine équipée ? (Raccordement machine à laver, évier...)'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCuisineAction'
				]
			]
		];

		$elements['cuisine']['raccordement'] = [
			'#type' => 'details',
			'#title' => 'Quels raccordements de plomberie souhaitez-vous ajouter ?',
			// '#description' => t('Lorem ipsum.'),
			'#open' => TRUE,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRaccordement'
				]
			]
		];

		$elements['cuisine']['raccordement']['evacuation'] = [
			'#type' => 'checkbox',
			'#title' => t('Raccordement et evacuation evier'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			]
		];

		$elements['cuisine']['raccordement']['evacuation-vaisselle'] = [
			'#type' => 'checkbox',
			'#title' => t('Raccordement et evacuation lave vaisselle'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			]
		];

		$elements['cuisine']['raccordement']['evacuation-linge'] = [
			'#type' => 'checkbox',
			'#title' => t('Raccordement et evacuation lave linge'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			]
		];
	}

	static function afterBuildRaccordement(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[raccordements]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static function afterBuildCuisineAction(array $element, FormStateInterface $form_state) {
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
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[piece]"]' => [
				'value' => 'cuisine'
			]
		];
		return $element;
	}

	static function afterBuildCuisine(array $element, FormStateInterface $form_state) {
		// dump($form_id);
		// $match = [];
		// Add #states targeting the specific element and table row.
		// preg_match('/^(.+)\[[^]]+]$/', $element['#name'], $match);
		// $composite_name = $match[1];
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
				'value' => 'cuisine'
			]
		];
		return $element;
	}
}