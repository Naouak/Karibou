<?php
/**
 * @copyright 2005 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 * 
 * @package framework
 **/

/**
 * Une liste de pages
 *
 * @todo inherit from a Factory ?
 * @package framework
 */ 

class ModelBuilder extends ObjectList
{	
	function __construct()
	{
		parent::__construct();
	}
	
	function build()
	{
		foreach($this as $model)
		{
			Debug::display("Building app: ".$model->appname." (model: ".get_class($model).")");
			ExecutionTimer::getRef()->start("Building Model ".$model->appname." (".get_class($model).")");
			$model->build();
			ExecutionTimer::getRef()->stop("Building Model ".$model->appname." (".get_class($model).")");
		}
	}
}

?>
