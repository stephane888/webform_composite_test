<?php
namespace Drupal\webform_composite_test\Services\Travaux;

use Drupal\Core\Form\FormStateInterface;

class Revetement {

	public function form(array &$elements) {
		$elements['revetemt'] = array(
			'#type' => 'details',
			'#title' => 'Révetement au sol',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRevetement'
				]
			]
		);

		$elements['revetemt']['revt-sol-old'] = [
			'#type' => 'radios',
			'#title' => t("Quel est le revêtement au sol actuel ?"),
			'#default_value' => 'non',
			'#options' => [
				'Carrelage' => 'Carrelage',
				'Parquet flottant' => 'Parquet flottant',
				'Parquet massif' => 'Parquet massif',
				'Lino/PVC' => 'Lino/PVC'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRevetementAction'
				]
			]
		];

		$elements['revetemt']['revt-preparation'] = [
			'#type' => 'radios',
			'#title' => t("Quelle préparation souhaitez-vous ?"),
			'#default_value' => 'non',
			'#options' => [
				'depot-revt' => 'Dépose du revêtement actuel',
				'pose-revt' => 'Pose sur revêtement actuel'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRevetementAction'
				]
			]
		];

		$elements['revetemt']['revt-sol-new'] = [
			'#type' => 'radios',
			'#title' => t("Quel nouveau revêtement souhaitez-vous ?"),
			'#default_value' => 'non',
			'#options' => [
				'carrelage' => 'Carrelage',
				'parquet-flottant' => 'Parquet flottant',
				'parquet-massif' => 'Parquet massif',
				'lino-pvc' => 'Lino/PVC',
				'moquette' => 'Moquette'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRevetementAction'
				]
			]
		];

		$elements['revetemt']['revt-sol-surface'] = [
			'#type' => 'textfield',
			'#title' => t(' Quelle est la surface en m² ? '),
			'#attributes' => [
				'placeholder' => 'Surface en m²'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildRevetementAction'
				]
			]
		];
	}

	static public function afterBuildRevetementAction(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[revetement]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildRevetement(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[revetement]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}
}