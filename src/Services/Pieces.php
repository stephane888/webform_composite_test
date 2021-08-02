<?php
namespace Drupal\webform_composite_test\Services;

use Drupal\Core\Form\FormStateInterface;
use Stephane888\Debug\debugLog;
use Drupal\webform_composite_test\Services\Medias\Medias;

/**
 *
 * @author stephane
 *         doc : https://evolvingweb.ca/blog/extending-form-api-states-regular-expressions
 */
class Pieces {

	protected $cuisine;

	protected $SalleDeBain;

	protected $Travaux;

	protected $Buanderie;

	protected $Toilettes;

	protected $Medias;

	function __construct(Travaux $Travaux, Cuisine $Cuisine, SalleDeBain $SalleDeBain, Buanderie $Buanderie, Toilettes $Toilettes, Medias $Medias) {
		$this->cuisine = $Cuisine;
		$this->SalleDeBain = $SalleDeBain;
		$this->Travaux = $Travaux;
		$this->Buanderie = $Buanderie;
		$this->Toilettes = $Toilettes;
		$this->Medias = $Medias;
	}

	public function form(array &$elements) {
		$elements['piece'] = [
			'#type' => 'select',
			'#title' => t('Piece'),
			'#required' => true,
			'#options' => [
				'cuisine' => 'Cuisine',
				'sdb' => 'Salle de bain',
				'salon' => 'Salon',
				'chambre' => 'Chambre',
				'salle_a_manger' => 'Salle à manger',
				'couloir' => 'Couloir',
				'bde' => 'Buanderie',
				'tle' => 'Toilettes',
				'dressing' => 'Dressing',
				'bureau' => 'Bureau'
			],
			'#after_build' => [
				[
					get_class($this),
					'afterBuildPieces'
				]
			]
		];

		$this->cuisine->form($elements);
		$this->SalleDeBain->form($elements);
		$this->Buanderie->form($elements);
		$this->Toilettes->form($elements);
		$this->Travaux->form($elements);
		$this->Medias->form($elements);
	}

	static function afterBuildPieces(array $element, FormStateInterface $form_state) {
		$value = 2;
		$property = 'nombre_piece';
		if ($form_state->get($property)) {
			$value ++;
		}
		$form_state->set($property, $value);
		// $element['#title'] = $element['#title'] . ' (' . $value . ')';

		return $element;
	}
}