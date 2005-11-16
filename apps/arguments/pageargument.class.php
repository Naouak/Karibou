<?php

class PageArgument extends Argument
{
	function getUrlArgument()
	{
		return "page-".$this->value;
	}
	
	function getVar($arg)
	{
		if( preg_match('#page-([0-9]+)#', $arg, $match) )
		{
			return $match[1];
		}
		return false;
	}
}

?>