<?php
class EmoticonArgument extends Argument {
	public function getVar($arg) {
		return basename(urldecode($arg));
	}

	public function getUrlArgument() {
		return urlencode($this->value);
	}
}
