<?php
namespace Drupal\webform_composite_test\Services\Travaux;

use Drupal\Core\Form\FormStateInterface;

class Cloisons {

	public function form(array &$elements) {
		$elements['cloison'] = array(
			'#type' => 'details',
			'#title' => 'Cloisons / Plafond',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCloisons'
				]
			]
		);

		$elements['cloison']['crrer'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous créer une cloison ?'),
			// '#required' => true,
			'#default_value' => 'non',
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCloisonsPlafondMur'
				]
			]
		];
		$this->CloisonsCreation($elements['cloison']);

		$elements['cloison']['crrer-surface-plafond'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous créer un plafond ?'),
			// '#required' => true,
			'#default_value' => 'non',
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCloisonsPlafondMur'
				]
			]
		];

		$elements['cloison']['cloison-plafond-surface'] = [
			'#type' => 'number',
			'#title' => t(' Quelle est la surface approximative du plafonds à créer ? '),
			// '#required' => true,
			'#attributes' => [
				'#placeholder' => 'surface en m²'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCloisonsPlafondSurface'
				]
			]
		];
	}

	function CloisonsCreation(array &$elements) {
		$elements['cls'] = array(
			'#type' => 'details',
			'#title' => 'Revêtements muraux',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCloisonsCreation'
				]
			]
		);

		$elements['cls']['type-cloisonnement'] = [
			'#type' => 'radios',
			'#title' => t('Quel type de cloisonnement souhaitez-vous ?'),
			'#default_value' => 'non',
			// '#required' => true,
			'#options' => [
				'Placoplâtre BA13' => 'Placoplâtre BA13',
				'Carreaux de plâtre' => 'Carreaux de plâtre'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCloisonsCreationTypeCloisonnement'
				]
			]
		];

		$elements['cls']['equip'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels équipements souhaitez-vous ?'
		];

		$elements['cls']['equip']['cls-verriere'] = [
			'#type' => 'checkbox',
			'#title' => t('Verrière'),
			// '#required' => true,
			'#default_value' => 'non'
		];

		$elements['cls']['equip']['cls-porte'] = [
			'#type' => 'checkbox',
			'#title' => t('Verrière'),
			// '#required' => true,
			'#default_value' => 'Porte'
		];

		$elements['cls']['cls-plafond'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t(' Quelle est la surface approximative de la cloison ( Verrière et porte incluses ) ? '),
			'#attributes' => [
				'placeholder' => 'surface en m²'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildCloisonsCreationTypeCloisonnement'
				]
			]
		];
	}

	static public function afterBuildCloisonsCreationTypeCloisonnement(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[crrer]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static public function afterBuildCloisonsPlafondSurface(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[crrer-surface-plafond]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static public function afterBuildCloisonsCreation(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[crrer]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static public function afterBuildCloisonsPlafondMur(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[cloisons]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildCloisons(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[cloisons]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}
}