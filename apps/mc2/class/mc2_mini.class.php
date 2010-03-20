<?php
class Mc2Mini extends Model {
	public function build() {
		$this->assign('invert', $this->args['invert']);
		$this->assign('button', $this->args['button']);
	}
}
