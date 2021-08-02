<?php
namespace Drupal\webform_composite_test\Services;

use Drupal\Core\Form\FormStateInterface;

class Toilettes {

	public function form(array &$elements) {
		$elements['tle'] = array(
			'#type' => 'details',
			'#title' => 'POSE ET DEPOSE TOILETTES',
			// '#description' => t('Lorem ipsum.'),
			'#open' => TRUE,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildToilettes'
				]
			],
			'#attributes' => [
				'class' => [
					'main-details'
				]
			],
			"#webform_id" => 'id-toilette',
			'#webform_key' => 'toilette'
		);

		$elements['tle']['ele-tle-supprime'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous supprimer des éléments de votre Toilettes (WC, meuble, lavabo, radiateur...) ?'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildToilettesAction'
				]
			]
		];
		$this->ToilettesRemove($elements['tle']);
		$elements['tle']['ele-tle-adds'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous ajouter des nouveaux équipements pour la Toilettes ? (Raccordement machine à laver, lavabo...) ?'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildToilettesAction'
				]
			]
		];
		$this->ToilettesAdd($elements['tle']);
	}

	function ToilettesAdd(array &$elements) {
		$elements['mee'] = array(
			'#type' => 'details',
			'#title' => 'Elements à ajouter',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildToilettesAdd'
				]
			]
		);

		$elements['mee']['element-add'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels équipements souhaitez-vous ajouter ?'
		];

		$elements['mee']['element-add']['tle-add-lavabo'] = [
			'#type' => 'checkbox',
			'#title' => t('Lavabo pose')
		];
		$elements['mee']['element-add']['tle-add-raccordement'] = [
			'#type' => 'checkbox',
			'#title' => t('Raccordement machine laver')
		];
		$elements['mee']['element-add']['tle-add-seche-s'] = [
			'#type' => 'checkbox',
			'#title' => t('Seche-serviette')
		];
		$elements['mee']['element-add']['tle-add-ballon'] = [
			'#type' => 'checkbox',
			'#title' => t('Ballon eau chaude')
		];
	}

	function ToilettesRemove(array &$elements) {
		$elements['me-re'] = array(
			'#type' => 'details',
			'#title' => 'Elements à supprimer',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildToilettesRemove'
				]
			]
		);

		$elements['me-re']['element-rm'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels sont les éléments à enlever ?'
		];

		$elements['me-re']['element-rm']['tle-rm-lavabo'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression lavabo ou meuble vasque')
		];

		$elements['me-re']['element-rm']['tle-rm-bidet'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression bidet')
		];

		$elements['me-re']['element-rm']['tle-rm-wc'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression WC')
		];

		$elements['me-re']['element-rm']['tle-rm-ws-susp'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression WC suspendus')
		];

		$elements['me-re']['element-rm']['tle-rm-ra-elect'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression radiateur electrique')
		];

		$elements['me-re']['element-rm']['tle-rm-ra-chaude'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression radiateur eau chaude')
		];
	}

	static function afterBuildToilettesAdd(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[ele-tle-adds]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static function afterBuildToilettesRemove(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[ele-tle-supprime]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static function afterBuildToilettesAction(array $element, FormStateInterface $form_state) {
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
				'value' => 'tle'
			]
		];
		return $element;
	}

	static function afterBuildToilettes(array $element, FormStateInterface $form_state) {
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
				'value' => 'tle'
			]
		];
		return $element;
	}
}