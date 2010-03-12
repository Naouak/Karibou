<?php
class Mc2Mini extends Model {
	public function build() {
		$this->assign('invert', $this->args['invert']);
	}
}
