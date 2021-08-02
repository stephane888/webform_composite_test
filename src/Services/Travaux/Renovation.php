<?php
namespace Drupal\webform_composite_test\Services\Travaux;

use Drupal\Core\Form\FormStateInterface;

class Renovation {

	public function form(array &$elements) {
		$elements['renovation'] = array(
			'#type' => 'details',
			'#title' => 'Renovation des murs / plafonds',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovation'
				]
			]
		);

		$elements['renovation']['revetements-mural'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous modifier-rénover les revêtements muraux ?'),
			'#default_value' => 'non',
			// '#required' => true,
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationPlafondMur'
				]
			]
		];

		// /////////////////////////////////////
		// Souhaitez-vous modifier-rénover les revêtements muraux ?
		$this->renovationMur($elements['renovation']);

		$elements['renovation']['plafond'] = [
			'#type' => 'radios',
			// '#required' => true,
			'#title' => t('Souhaitez-vous repeindre le plafond ?'),
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationPlafondMur'
				]
			]
		];

		// /////////////////////////////////////
		// Souhaitez-vous repeindre le plafond ?
		$this->renovationPlafond($elements['renovation']);
	}

	function renovationMur(array &$elements) {
		$elements['mur'] = array(
			'#type' => 'details',
			'#title' => 'Revêtements muraux',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationMur'
				]
			]
		);

		$elements['mur']['revet-old'] = [
			'#type' => 'radios',
			'#title' => t("Quel est le revêtement mural actuel ?"),
			// '#required' => true,
			'#default_value' => 'non',
			'#options' => [
				'Peinture' => 'Peinture',
				'Toile de verre' => 'Toile de verre',
				'Lambris' => 'Lambris',
				'Papier peint' => 'Papier peint'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationCreerMur'
				]
			]
		];

		$elements['mur']['revet-new'] = [
			'#type' => 'radios',
			// '#required' => true,
			'#title' => t("Quel nouveau revêtement souhaitez-vous ?"),
			'#default_value' => 'non',
			'#options' => [
				'Peinture' => 'Peinture',
				'Toile de verre' => 'Toile de verre',
				'Papier peint' => 'Papier peint'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationCreerMur'
				]
			]
		];

		$elements['mur']['etat-mur'] = [
			'#type' => 'radios',
			// '#required' => true,
			'#title' => t("Quel est l'état global des murs ?"),
			'#default_value' => 'non',
			'#options' => [
				20 => 'Bon etat',
				30 => 'Etat moyen',
				40 => 'Mauvais etat'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationCreerMur'
				]
			]
		];

		$elements['mur']['surface-mur'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t('Quelle est la surface approximative des murs en m² ?'),
			'#attributes' => [
				'#placeholder' => 'surface en m²'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationCreerMur'
				]
			]
		];
	}

	function renovationPlafond(array &$elements) {
		$elements['plafonds'] = array(
			'#type' => 'details',
			'#title' => 'Plafonds',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationPlafond'
				]
			]
		);
		$elements['plafonds']['etat-plafond'] = [
			'#type' => 'radios',
			'#title' => t("Quel est l'état global du plafond ?"),
			// //'#required' => true,
			'#options' => [
				20 => 'Bon etat',
				30 => 'Etat moyen',
				40 => 'Mauvais etat'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationCreerPlafond'
				]
			]
		];

		$elements['plafonds']['surface-plafond'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t('Quelle est la surface approximative du plafonds ? '),
			'#attributes' => [
				'placeholder' => 'surface en m²'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRenovationCreerPlafond'
				]
			]
		];
	}

	static public function afterBuildRenovationCreerPlafond(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[plafond]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static public function afterBuildRenovationCreerMur(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[revetements-mural]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static public function afterBuildRenovationMur(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[revetements-mural]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static public function afterBuildRenovationPlafond(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[plafond]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static public function afterBuildRenovationPlafondMur(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[renovation]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildRenovation(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[renovation]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}
}