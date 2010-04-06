<?php
/**
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 */

class EmoticonsList extends Model {
	public function build() {
		$e = new Emoticons(null);

		return json_encode($e->getMatchArray($this->args['theme']));
	}
}
