<?php
class Mc2JS extends Model {
	public function build() {
		$this->assign('user', $this->currentUser);
	}
}
