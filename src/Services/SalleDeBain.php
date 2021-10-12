<?php
namespace Drupal\webform_composite_test\Services;

use Drupal\Core\Form\FormStateInterface;

class SalleDeBain {

	public function form(array &$elements) {
		$elements['sdb'] = array(
			'#type' => 'details',
			'#title' => 'POSE ET DEPOSE SDB',
			// '#description' => t('Lorem ipsum.'),
			'#open' => TRUE,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildSdb'
				]
			],
			'#attributes' => [
				'class' => [
					'main-details'
				]
			],
			"#webform_id" => 'id-sdb',
			'#webform_key' => 'sdb'
		);

		$elements['sdb']['element-supprime'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous supprimer des éléments de votre salle de bain (Douche, baignoire, WC, meuble, lavabo, radiateur...) ?'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildSdbAction'
				]
			]
		];

		$this->SalleDeBainRemove($elements['sdb']);

		$elements['sdb']['element-adds'] = [
			'#type' => 'radios',
			'#title' => t('Souhaitez-vous ajouter des nouveaux équipements pour la salle de bain ? (Douche, baignoire, WC, lavabo...)?'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => t('Oui'),
				'non' => t('Non')
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildSdbAction'
				]
			]
		];

		$this->SalleDeBainAdd($elements['sdb']);
	}

	function SalleDeBainAdd(array &$elements) {
		$elements['me-re'] = array(
			'#type' => 'details',
			'#title' => 'Revêtements muraux',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildSalleDeBainAdd'
				]
			]
		);

		$elements['me-re']['element-add'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels équipements souhaitez-vous ajouter ?'
		];

		$elements['me-re']['element-add']['sdb-add-douche-cabine'] = [
			'#type' => 'checkbox',
			'#title' => t('Douche cabine')
		];

		$elements['me-re']['element-add']['sdb-add-baignoire'] = [
			'#type' => 'checkbox',
			'#title' => t('Baignoire')
		];

		$elements['me-re']['element-add']['sdb-add-lavabo'] = [
			'#type' => 'checkbox',
			'#title' => t('Lavabo pose')
		];

		$elements['me-re']['element-add']['sdb-add-wc'] = [
			'#type' => 'checkbox',
			'#title' => t('WC pose')
		];

		$elements['me-re']['element-add']['sdb-add-wc-suspendus'] = [
			'#type' => 'checkbox',
			'#title' => t('WC suspendus')
		];

		$elements['me-re']['element-add']['sdb-add-machine-laver'] = [
			'#type' => 'checkbox',
			'#title' => t('Raccordement machine laver')
		];

		$elements['me-re']['element-add']['sdb-add-ballon-chaude'] = [
			'#type' => 'checkbox',
			'#title' => t('Ballon eau chaude')
		];
	}

	function SalleDeBainRemove(array &$elements) {
		$elements['ment-re'] = array(
			'#type' => 'details',
			'#title' => 'Revêtements muraux',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildSalleDeBainRemove'
				]
			]
		);

		$elements['ment-re']['element-remove'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels sont les éléments à enlever ?'
		];

		$elements['ment-re']['element-remove']['sdb-rm-encastree'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression douche encastree')
		];

		$elements['ment-re']['element-remove']['sdb-rm-cabine'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression cabine de douche'),
			'#default_value' => 'non',
			'#options' => [
				'oui' => 'oui',
				'non' => 'non'
			]
		];

		$elements['ment-re']['element-remove']['sdb-rm-baignoire'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression baignoire')
		];

		$elements['ment-re']['element-remove']['sdb-rm-lavabo'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression lavabo ou meuble vasque')
		];

		$elements['ment-re']['element-remove']['sdb-rm-bidet'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression bidet')
		];

		$elements['ment-re']['element-remove']['sdb-rm-wc'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression WC')
		];

		$elements['ment-re']['element-remove']['sdb-rm-wc-suspendus'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression WC suspendus')
		];

		$elements['ment-re']['element-remove']['sdb-rm-radiateur'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression radiateur electrique')
		];

		$elements['ment-re']['element-remove']['sdb-rm-radiateur-chaude'] = [
			'#type' => 'checkbox',
			'#title' => t('Suppression radiateur eau chaude')
		];
	}

	static function afterBuildSalleDeBainAdd(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[element-adds]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static function afterBuildSalleDeBainRemove(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[element-supprime]"]' => [
				'value' => 'oui'
			]
		];
		return $element;
	}

	static function afterBuildSdbAction(array $element, FormStateInterface $form_state) {
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
				'value' => 'sdb'
			]
		];
		return $element;
	}

	static function afterBuildSdb(array $element, FormStateInterface $form_state) {
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
				'value' => 'sdb'
			]
		];
		return $element;
	}
}