<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 **/

require_once dirname(__FILE__).'/smarty/kurl.function.php';

/**
 * Choose which form to execute
 *
 * @package framework
 */
class FormParser
{
	protected $pagename;
	protected $modelname;
	
	protected $referer = false;
	protected $redirect = false;
	protected $redirect_args;

	function __construct($pagename, $modelname)
	{
		$this->pagename = $pagename;
		$this->modelname = $modelname;
	}
	
	function getName()
	{
		return $this->pagename;
	}
	
	function getModelName()
	{
		return $this->modelname;
	}
	
	function referer()
	{
		$this->referer = true;
	}
	function redirect()
	{
		$this->redirect = true;
	}
	
	function setRedirectArg($name, $value)
	{
		$this->redirect_args[$name] = $value;
	}

	function getLocation(AppList $appList)
	{
		if($this->referer)
		{
			return $_SERVER['HTTP_REFERER'];
		}
		else if($this->redirect)
		{
			return kurl( $this->redirect_args , $appList);
		}
		return false;
	}
}

?>
