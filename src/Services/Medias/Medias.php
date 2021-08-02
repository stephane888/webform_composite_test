<?php
namespace Drupal\webform_composite_test\Services\Medias;

use Drupal\Core\Form\FormStateInterface;

// gif jpg jpeg png bmp eps tif pict psd txt rtf html odf pdf doc docx ppt pptx xls xlsx xml avi mov mp3 mp4 ogg wav bz2 dmg gz jar rar sit svg tar zip
class Medias {

	function form(array &$elements) {
		$elements['medias'] = [
			'#type' => 'details',
			'#title' => 'Ajouter les Photos/Videos/Documents',
			// '#description' => t('Quels travaux voulez-vous effectuer dans cette pièce ?'),
			'#open' => FALSE,
			'#after_build' => [
				[
					get_class($this),
					'afterBuildMedias'
				]
			]
		];

		$elements['medias']['piece-photos-1'] = [
			'#type' => 'managed_file',
			'#title' => 'Media 1 (photos, videos, PDF ...)',
			'#max_filesize' => '150',
			'#file_extensions' => 'pdf doc docx zip jpg jpeg png webp mov mp3 mp4 ogg wav',
			'#sanitize' => true
		];

		$elements['medias']['piece-photos-2'] = [
			'#type' => 'managed_file',
			'#title' => 'Media 2 (photos, videos, PDF ...)',
			'#max_filesize' => '150',
			'#file_extensions' => 'pdf doc docx zip jpg jpeg png webp mov mp3 mp4 ogg wav',
			'#sanitize' => true
		];

		$elements['medias']['piece-videos-3'] = [
			'#type' => 'managed_file',
			'#title' => 'Media 3 (photos, videos, PDF ...)',
			'#max_filesize' => '150',
			'#file_extensions' => 'pdf doc docx zip jpg jpeg png webp mov mp3 mp4 ogg wav',
			'#sanitize' => true
		];
	}

	static function afterBuildMedias(array $element, FormStateInterface $form_state) {
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
				[
					'value' => 'cuisine'
				],
				[
					'or'
				],
				[
					'value' => 'sdb'
				],
				[
					'or'
				],
				[
					'value' => 'salon'
				],
				[
					'or'
				],
				[
					'value' => 'chambre'
				],
				[
					'or'
				],
				[
					'value' => 'salle_a_manger'
				],
				[
					'or'
				],
				[
					'value' => 'couloir'
				],
				[
					'or'
				],
				[
					'value' => 'bde'
				],
				[
					'or'
				],
				[
					'value' => 'tle'
				],
				[
					'or'
				],
				[
					'value' => 'dressing'
				],
				[
					'or'
				],
				[
					'value' => 'bureau'
				]
			]
		];
		return $element;
	}
}
