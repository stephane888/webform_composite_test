<?php
namespace Drupal\webform_composite_test\Services;

class ListPrice {

	/**
	 *
	 * @param string $key
	 * @param array $sumission
	 * @throws \Exception
	 * @return number|number
	 */
	public function getPrice($key, $sumission) {
		if (! empty($sumission[$key]) && $sumission[$key] == 'non')
			return 0;
		$value = $this->lists($key);
		if ($value) {

			if (isset($value['field'])) {
				// Cas ou a un seul champs à multuplier.
				if (! is_array($value['field'])) {
					if (! empty($sumission[$value['field']])) {
						return $sumission[$value['field']] * $value['cout'];
					}
					else {
						\Drupal::messenger()->addWarning('valeur du paramettre non definie : ' . $value['field']);
						return 0;
					}
				}
				else {
					$cout = 1;
					foreach ($value['field'] as $field) {
						if (! empty($sumission[$field])) {
							$cout = ($sumission[$field] * $value['cout']) * $cout;
						}
						else {
							\Drupal::messenger()->addWarning('valeur du paramettre non definie : ' . $value['field']);
							return 0;
						}
					}
					return $cout;
				}
			}
			elseif (isset($value['custom'])) {
				$value = $value['custom'];
				return $this->$value($value, $key, $sumission);
			}
			else {
				return $value['cout'];
			}
		}
	}

	public function lists($key) {
		$prices = [
			// travaux electricités.
			'prise-electr' => [
				'field' => 'nombre-prise',
				'cout' => 100
			],
			'prise-electr-travail' => [
				'field' => 'nombre-prise-travail',
				'cout' => 100
			],
			'prise-electr-four' => [
				'field' => 'nombre-prise-four',
				'cout' => 100
			],
			'prise-electr-vaisselle' => [
				'field' => 'nombre-prise-vaiselle',
				'cout' => 100
			],
			'prise-electr-linge' => [
				'field' => 'nombre-prise-linge',
				'cout' => 100
			],
			'prise-electr-hotte' => [
				'field' => 'nombre-prise-hotte',
				'cout' => 100
			],
			'prise-electr-plaque' => [
				'field' => 'nombre-prise-32a',
				'cout' => 150
			],
			'prise-electr-interupteur' => [
				'field' => 'nombre-prise-interrupteurs',
				'cout' => 150
			],
			'prise-electr-va-vient' => [
				'field' => 'nombre-prise-va-vient',
				'cout' => 150
			],
			'prise-electr-plafonnier' => [
				'field' => 'nombre-prise-plafonniers',
				'cout' => 100
			],
			'prise-electr-applique' => [
				'field' => 'nombre-prise-aplliq',
				'cout' => 100
			],
			'prise-electr-encastrer' => [
				'field' => 'nombre-prise-spot',
				'cout' => 100
			],
			'prise-electr-radiateur' => [
				'field' => 'nombre-prise-radiateur',
				'cout' => 100
			],
			'prise-electr-rj45' => [
				'field' => 'nombre-prise-rj45',
				'cout' => 100
			],
			// cuisine
			'demonter' => [
				'cout' => 300
			],
			'evacuation-vaisselle' => [
				'cout' => 300
			],
			'evacuation-linge' => [
				'cout' => 300
			],
			'evacuation' => [
				'cout' => 300
			],
			// travaux plafonds
			'plafond' => [
				'cout' => 1,
				'field' => [
					'etat-plafond',
					'surface-plafond'
				]
			],
			'revetements-mural' => [
				'cout' => 1,
				'field' => [
					'etat-mur',
					'surface-mur'
				]
			],
			// Travaux cloisons
			'crrer-surface-plafond' => [
				'cout' => 50, // 50 / mettre carrée.
				'field' => 'cloison-plafond-surface'
			],
			'type-cloisonnement' => [
				'cout' => 50,
				'field' => 'cls-plafond'
			],
			'cls-porte' => [
				'cout' => 150
			],
			'cls-verriere' => [
				'cout' => 200
			],
			// Travaux revetements sol
			'revetement' => [
				'cout' => 1,
				'custom' => 'revetement_sol'
			],
			// Salle de bain
			'sdb-rm-encastree' => [
				'cout' => 300
			],
			'sdb-rm-cabine' => [
				'cout' => 300
			],
			'sdb-rm-baignoire' => [
				'cout' => 300
			],
			'sdb-rm-lavabo' => [
				'cout' => 200
			],
			'sdb-rm-bidet' => [
				'cout' => 200
			],
			'sdb-rm-wc' => [
				'cout' => 200
			],
			'sdb-rm-wc-suspendus' => [
				'cout' => 300
			],
			'sdb-rm-radiateur' => [
				'cout' => 100
			],
			'sdb-rm-radiateur-chaude' => [
				'cout' => 200
			],
			'sdb-add-douche-cabine' => [
				'cout' => 1000
			],
			'sdb-add-baignoire' => [
				'cout' => 1000
			],
			'sdb-add-lavabo' => [
				'cout' => 300
			],
			'sdb-add-wc' => [
				'cout' => 350
			],
			'sdb-add-wc-suspendus' => [
				'cout' => 1000
			],
			'sdb-add-machine-laver' => [
				'cout' => 300
			],
			'sdb-add-ballon-chaude' => [
				'cout' => 1000
			],
			// Buanderie
			'bde-rm-lavabo' => [
				'cout' => 200
			],
			'bde-rm-bidet' => [
				'cout' => 200
			],
			'bde-rm-wc' => [
				'cout' => 200
			],
			'bde-rm-ws-susp' => [
				'cout' => 300
			],
			'bde-rm-ra-elect' => [
				'cout' => 100
			],
			'bde-rm-ra-chaude' => [
				'cout' => 200
			],
			'bde-add-lavabo' => [
				'cout' => 300
			],
			'bde-add-raccordement' => [
				'cout' => 300
			],
			'bde-add-seche-s' => [
				'cout' => 600
			],
			'bde-add-ballon' => [
				'cout' => 1000
			],
			// Toilette
			'tle-rm-lavabo' => [
				'cout' => 200
			],
			'tle-rm-bidet' => [
				'cout' => 200
			],
			'tle-rm-wc' => [
				'cout' => 200
			],
			'tle-rm-ws-susp' => [
				'cout' => 300
			],
			'tle-rm-ra-elect' => [
				'cout' => 100
			],
			'tle-rm-ra-chaude' => [
				'cout' => 200
			],
			'tle-add-lavabo' => [
				'cout' => 300
			],
			'tle-add-raccordement' => [
				'cout' => 300
			],
			'tle-add-seche-s' => [
				'cout' => 600
			],
			'tle-add-ballon' => [
				'cout' => 1000
			]
			//
			// 'evacuation' => [
			// 'cout' => 300
			// ],
			// 'evacuation' => [
			// 'cout' => 300
			// ],
			// 'evacuation' => [
			// 'cout' => 300
			// ]
		];

		if (! empty($prices[$key])) {
			return $prices[$key];
		}
		return null;
	}

	/**
	 *
	 * @param array $value
	 * @param string $key
	 * @param array $sumission
	 */
	private function revetement_sol($value, $key, $sumission) {
		// dump($sumission);
		// champs utilisé;
		$fields = [
			'revt-preparation',
			'revt-sol-new',
			'revt-sol-surface'
		];
		foreach ($fields as $field_name) {
			if (! isset($sumission[$field_name])) {
				// throw new \Exception('valeur du paramettre non definie : ' . $value[$field_name] . ' :: clee ' . $field_name);
				\Drupal::messenger()->addWarning('valeur du paramettre non definie : ' . $field_name);
				return 0;
			}
		}
		// calcul
		switch ($sumission['revt-preparation']) {
			case 'depot-revt':
				return ($this->getValue_revtSolNew($sumission['revt-sol-new'], $sumission['revt-preparation']) * $sumission['revt-sol-surface']);
				break;

			case 'pose-revt':
				return ($this->getValue_revtSolNew($sumission['revt-sol-new'], $sumission['revt-preparation']) * $sumission['revt-sol-surface']);
				break;
		}
	}

	private function getValue_revtSolNew($key, $revtPreparation) {
		$array = [
			'carrelage' => ($revtPreparation == 'depot-revt') ? 60 : 30,
			'parquet-flottant' => ($revtPreparation == 'depot-revt') ? 30 : 10,
			'parquet-massif' => ($revtPreparation == 'depot-revt') ? 40 : 30,
			'lino-pvc' => ($revtPreparation == 'depot-revt') ? 10 : 25,
			'moquette' => 10
		];
		return $array[$key];
	}
}