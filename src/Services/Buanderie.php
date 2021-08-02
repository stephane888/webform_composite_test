<?php
namespace Drupal\webform_composite_test\Services;

use Drupal\Core\Form\FormStateInterface;

class Buanderie {

	public function form(array &$elements) {
		$elements['bde'] = array(
			'#type' => 'details',
			'#title' => 'POSE ET DEPOSE BUANDERIE',
			// '#description' => t('Lorem ipsum.'),
			'#open' => TRUE,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildBuanderie'
				]
			],
			'#attributes' => [
				'class' => [
					'main-details'
				]
			]
		);

		$elements['bde']['ele-bde-supprime'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous supprimer des éléments de votre buanderie (WC, meuble, lavabo, radiateur...) ?'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildBuanderieAction'
				]
			]
		];
		$this->BuanderieRemove($elements['bde']);

		$elements['bde']['ele-bde-adds'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous ajouter des nouveaux équipements pour la buanderie ? (Raccordement machine à laver, lavabo...) ?'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildBuanderieAction'
				]
			]
		];
		$this->BuanderieAdd($elements['bde']);
	}

	function BuanderieAdd(array &$elements) {
		$elements['mee'] = array(
			'#type' => 'details',
			'#title' => 'Elements à ajouter',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildBuanderieAdd'
				]
			]
		);

		$elements['mee']['element-add'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels équipements souhaitez-vous ajouter ?'
		];

		$elements['mee']['element-add']['bde-add-lavabo'] = [
			'#type' => 'checkbox',
			'#title' => t('Lavabo pose')
		];
		$elements['mee']['element-add']['bde-add-raccordement'] = [
			'#type' => 'checkbox',
			'#title' => t('Raccordement machine laver')
		];
		$elements['mee']['element-add']['bde-add-seche-s'] = [
			'#type' => 'checkbox',
			'#title' => t('Seche-serviette')
		];
		$elements['mee']['element-add']['bde-add-ballon'] = [
			'#type' => 'checkbox',
			'#title' => t('Ballon eau chaude')
		];
	}

	function BuanderieRemove(array &$elements) {
		$elements['me-re'] = array(
			'#type' => 'details',
			'#title' => 'Elements à supprimer',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildBuanderieRemove'
				]
			]
		);

		$elements['me-re']['element-rm'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels sont les éléments à enlever ?'
		];

		$elements['me-re']['element-rm']['bde-rm-lavabo'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression lavabo ou meuble vasque')
		];

		$elements['me-re']['element-rm']['bde-rm-bidet'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression bidet')
		];

		$elements['me-re']['element-rm']['bde-rm-wc'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression WC')
		];

		$elements['me-re']['element-rm']['bde-rm-ws-susp'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression WC suspendus')
		];

		$elements['me-re']['element-rm']['bde-rm-ra-elect'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression radiateur electrique')
		];

		$elements['me-re']['element-rm']['bde-rm-ra-chaude'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression radiateur eau chaude')
		];
	}

	static function afterBuildBuanderieAdd(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[ele-bde-adds]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static function afterBuildBuanderieRemove(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[ele-bde-supprime]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static function afterBuildBuanderieAction(array $element, FormStateInterface $form_state) {
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
				'value' => 'bde'
			]
		];
		return $element;
	}

	static function afterBuildBuanderie(array $element, FormStateInterface $form_state) {
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
				'value' => 'bde'
			]
		];
		return $element;
	}
}