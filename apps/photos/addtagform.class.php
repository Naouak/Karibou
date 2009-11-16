<?php
/**
 *@copyright 2009 Gilles DEHAUDT <tonton1728@gmail.com>
 */

class AddTagForm extends PhotosFormModel {
	public function build() {
		$tags = filter_input(INPUT_POST,"tag",FILTER_SANITIZE_SPECIAL_CHARS);
		$id = filter_input(INPUT_POST,"id",FILTER_SANITIZE_NUMBER_INT);

		
		$type = filter_input(INPUT_POST,"type",FILTER_SANITIZE_SPECIAL_CHARS);

		$this->insertNewTags($tags,$type,$id);
		
 		$this->setRedirectArg("page",$type);
		$this->setRedirectArg("id",$id);
	}
}
