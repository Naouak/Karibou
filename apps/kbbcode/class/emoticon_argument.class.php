<?php
/**
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 */

class EmoticonArgument extends Argument {
	public function getVar($arg) {
		return basename(urldecode($arg));
	}

	public function getUrlArgument() {
		return urlencode($this->value);
	}
}
