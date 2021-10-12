<?php
namespace Drupal\webform_composite_test\Services\Travaux;

use Drupal\Core\Form\FormStateInterface;

class Electricite {

	public function form(array &$elements) {
		$elements['electri'] = array(
			'#type' => 'details',
			'#title' => 'Electricité',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => true,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricite'
				]
			]
		);

		$elements['electri']['type-install'] = [
			'#type' => 'radios',
			'#title' => t("Quelle type d'installation désirez-vous ?"),
			'#default_value' => 'non',
			// '#required' => true,
			'#options' => [
				'Sous goulotte' => 'Sous goulotte',
				'Encastrée en saignée' => 'Encastrée en saignée'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectriciteTypeInstall'
				]
			]
		];

		$elements['electri']['selec'] = [
			'#type' => 'html_tag',
			'#tag' => 'div',
			'#value' => 'Quels équipements désirez-vous ?'
		];

		$elements['electri']['selec']['prise-electr'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise electrique')
		];
		$elements['electri']['selec']['prise-electr-travail'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise electrique sur plan de travail')
		];
		$elements['electri']['selec']['prise-electr-four'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise four')
		];
		$elements['electri']['selec']['prise-electr-vaisselle'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise lave vaisselle')
		];
		$elements['electri']['selec']['prise-electr-linge'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise lave linge')
		];
		$elements['electri']['selec']['prise-electr-hotte'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise hotte')
		];
		$elements['electri']['selec']['prise-electr-plaque'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise 32 A pour plaques électriques')
		];
		$elements['electri']['selec']['prise-electr-interupteur'] = [
			'#type' => 'checkbox',
			'#title' => t('Interrupteur ou variateur')
		];
		$elements['electri']['selec']['prise-electr-va-vient'] = [
			'#type' => 'checkbox',
			'#title' => t('Interrupteur Va et vient ou bouton poussoir')
		];
		$elements['electri']['selec']['prise-electr-plafonnier'] = [
			'#type' => 'checkbox',
			'#title' => t('Plafonnier')
		];
		$elements['electri']['selec']['prise-electr-applique'] = [
			'#type' => 'checkbox',
			'#title' => t('Applique')
		];
		$elements['electri']['selec']['prise-electr-encastrer'] = [
			'#type' => 'checkbox',
			'#title' => t('Spot encastre')
		];
		$elements['electri']['selec']['prise-electr-radiateur'] = [
			'#type' => 'checkbox',
			'#title' => t('Radiateur electrique')
		];
		$elements['electri']['selec']['prise-electr-rj45'] = [
			'#type' => 'checkbox',
			'#title' => t('Prise RJ45 ou antenne TV')
		];

		$elements['electri']['nombre-prise'] = [
			'#type' => 'number',
			'#title' => t(" Merci d'indiquer le nombre de prises electriques que vous souhaitez "),
			// '#required' => true,
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePrise'
				]
			]
		];

		$elements['electri']['nombre-prise-travail'] = [
			'#type' => 'number',
			'#title' => t("Merci d'indiquer le nombre de prises electriques sur plan de travail que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseTrl'
				]
			]
		];

		$elements['electri']['nombre-prise-four'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de prises four que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseFour'
				]
			]
		];

		$elements['electri']['nombre-prise-vaiselle'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de prises lave vaisselle que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseVaiselle'
				]
			]
		];

		$elements['electri']['nombre-prise-linge'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de prises lave linge que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseLinge'
				]
			]
		];

		$elements['electri']['nombre-prise-hotte'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de prises hotte que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseHotte'
				]
			]
		];

		$elements['electri']['nombre-prise-32a'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de prises 32 A pour plaques électriques que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePrise32A'
				]
			]
		];

		$elements['electri']['nombre-prise-interrupteurs'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre d'interrupteurs ou variateurs que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseInterup'
				]
			]
		];

		$elements['electri']['nombre-prise-va-vient'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre d'nterrupteurs Va et vient ou boutons poussoirs que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseVaVient'
				]
			]
		];

		$elements['electri']['nombre-prise-plafonniers'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de plafonniers que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePrisePlafond'
				]
			]
		];

		$elements['electri']['nombre-prise-aplliq'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre d'appliques que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseAppliq'
				]
			]
		];

		$elements['electri']['nombre-prise-spot'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de spots encastrés que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseSpot'
				]
			]
		];

		$elements['electri']['nombre-prise-radiateur'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de radiateurs electriques que vous souhaitez"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseRadiateur'
				]
			]
		];

		$elements['electri']['nombre-prise-rj45'] = [
			'#type' => 'number',
			// '#required' => true,
			'#title' => t("Merci d'indiquer le nombre de prise(s) RJ45 ou TV que vous souhaitez ?"),
			'#attributes' => [
				'placeholder' => 'Nombre de prise'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildElectricitePriseRj45'
				]
			]
		];
	}

	static public function afterBuildElectricitePriseRj45(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-rj45]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-rj45]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseRadiateur(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-radiateur]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-radiateur]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseSpot(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-encastrer]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-encastrer]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseAppliq(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-applique]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-applique]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePrisePlafond(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-plafonnier]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-plafonnier]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseVaVient(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-va-vient]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-va-vient]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseInterup(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-interupteur]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-interupteur]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePrise32A(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-plaque]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-plaque]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseHotte(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-hotte]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-hotte]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseLinge(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-linge]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-linge]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseVaiselle(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-vaisselle]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-vaisselle]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseFour(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-four]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-four]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePriseTrl(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr-travail]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr-travail]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectriciteTypeInstall(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[electricite]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricitePrise(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[prise-electr]"]' => [
				'checked' => TRUE
			]
		];
		$element['#states']['required'] = [
			':input[name="' . $composite_name . '[prise-electr]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}

	static public function afterBuildElectricite(array $element, FormStateInterface $form_state) {
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
			':input[name="' . $composite_name . '[electricite]"]' => [
				'checked' => TRUE
			]
		];
		return $element;
	}
}