<?php

/**
 *@copyright Gilles DEHAUDT <tonton1728@gmail.com>
 **/

/**
 * Add photos class
 * @package Application
 **/

class AddPhotosForm extends PhotosFormModel {
	public function build() {
		$tags = filter_input(INPUT_POST,"tags",FILTER_SANITIZE_SPECIAL_CHARS);
		$filename = filter_input(INPUT_POST,"name",FILTER_SANITIZE_SPECIAL_CHARS);
		$parent = filter_input(INPUT_POST,"parent",FILTER_SANITIZE_NUMBER_INT);

		$photosdir = KARIBOU_PUB_DIR."/photos";

		if (!is_dir($photosdir)) {
			mkdir( $photosdir, 0744, true);
		}

		if(is_uploaded_file($filename) && filesize($filename)) {
			$img = imagecreatefromstring(file_get_contents($filename));
			copy($filename,$photosdir."/"."test".".jpg");
		}

		$idphotos = $this->addPicture($parent);

		$this->insertNewTags($tags,"album",$idphotos);
	}
}