<?php
/**
 * @copyright 2005 Antoine Leclercq <http://antoine.leclercq.netcv.org>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 *
 * @package applications
 **/

/**
 *
 * @package applications
 **/
class FileShareCreateDirectory extends Model
{
	public function build()
	{
		if (isset($this->args['directoryname']))
		{
			$this->assign('directorynamebase64', $this->args['directoryname']);
			$this->assign('directoryname', base64_decode($this->args['directoryname']));
		}
	}
}

?>