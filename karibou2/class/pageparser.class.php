<?php
/**
 * @copyright 2004 Jonathan Semczyk <jonathan.semczyk@free.fr>
 *
 * @license http://www.gnu.org/licenses/lgpl.html Lesser GNU Public License
 * See the enclosed file COPYING for license information (LGPL).
 *
 * @package framework
 **/

/**
 * Choose which page to display
 *
 * @package framework
 */
class PageParser
{
	protected $name;
	protected $viewname;
	protected $args;
	protected $contentType;
	
	protected $header;
	protected $footer;
	
	function __construct($name, $viewname)
	{
		$this->name = $name;
		$this->viewname = $viewname;
		$this->args = array();
	}
	
	function getName()
	{
		return $this->name;
	}
	
	function getViewName()
	{
		return $this->viewname;
	}
		
	function getHeader()
	{
		return $this->header;
	}
	function setHeader($app, $view)
	{
		$this->header = array( 'app' => $app, 'view' => $view);
	}
	function getFooter()
	{
		return $this->footer;
	}
	function setFooter($app, $view)
	{
		$this->footer = array( 'app' => $app, 'view' => $view);
	}

	function setContentType($type) {
		$this->contentType = $type;
	}

	function getContentType($type) {
		return $this->contentType;
	}

	/**
	 * On pile les arguments possibles pour cette page
	 */
	function manageArgument($argName, Argument $argumentParser, $must=true)
	{
		if( isset($this->args[$argName]) ) Debug::kill("La page ".$this->name." possede déjà un argument du nom de : ".$argName);
		$this->args[$argName] = array(
			"parser" => $argumentParser, 
			"must" => $must) ;
	}
	
	/**
	 * Vrai si les arguments sont conformes
	 * 
	 * @param $argsTab le tableau des arguments de l'url
	 * @return bool
	 */
	function match( $argsTab )
	{
		if( count($argsTab) > count($this->args) )
		{
			return FALSE;
		}
		if( count($argsTab)==0 && count($this->args)==0 )
		{
			return TRUE;
		}
		$ret = FALSE;
		foreach( $this->args as $name => $argParser )
		{
			$fetchArg = FALSE;
			foreach( $argsTab as $key => $arg )
			{
				if( ($value = $argParser["parser"]->getVar($arg)) !== false )
				{
					$argParser["parser"]->setValue($value);
					$fetchArg = TRUE;
					$tmp = array();
					foreach( $argsTab as $tmp_key => $tmp_arg )
					{
						if( $key != $tmp_key )
							$tmp[] = $tmp_arg;
					}
					$argsTab = $tmp;
					break;
				}
			}
			if( ($fetchArg === FALSE) && ($argParser["must"] === TRUE) )
			{
				$ret = FALSE;
				break;
			}
			$ret = TRUE;
		}
		return $ret;
	}
	
	function getArguments()
	{
		$args = array();
		foreach( $this->args as $name => $argParser )
		{
			$args[$name] = $argParser["parser"]->getValue();
		}
		return $args;
	}
}

?>
