<?php

class WeekArgument extends Argument
{
	//Permet d'ecrire l'argument dans l'URL
	function getUrlArgument()
	{
		return 'w'.$this->value;
	}
	
	//A l'URL en argument, retourne la valeur si elle la trouve sinon retourne FALSE
	function getVar($arg)
	{
		if( preg_match('#w([0-9]{1,2})#', $arg, $match) )
		{
			return $match[1];
		}
		return false;
	}
}

?>