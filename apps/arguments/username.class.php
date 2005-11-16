<?php

class UserName extends Argument
{
	function getUrlArgument()
	{
		return "~".$this->value;
	}
	
	function getVar($arg)
	{
		if( preg_match('/~([a-zA-Z0-9\.\-_]+)/', $arg, $match) )
		{
			return $match[1];
		}
		return false;
	}
}

?>
