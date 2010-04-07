<?php
/**
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 */

class Mc2Mini extends Model {
	public function build() {
		$this->assign('invert', $this->args['invert']);
		$this->assign('button', $this->args['button']);
	}
}
