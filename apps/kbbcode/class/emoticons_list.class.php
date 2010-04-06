<?php
class EmoticonsList extends Model {
	public function build() {
		$e = new Emoticons(null);

		echo json_encode($e->getMatchArray($this->args['theme']));
	}
}
