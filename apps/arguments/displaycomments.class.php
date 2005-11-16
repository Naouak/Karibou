<?php

class DisplayComments extends Argument
{
	//Permet d'ecrire l'argument dans l'URL
	function getUrlArgument()
	{
		return "dc".$this->value;
	}
	
	//A l'URL en argument, retourne la valeur si elle la trouve sinon retourne FALSE
	function getVar($arg)
	{
		if( preg_match('#dc([0,1]{1})#', $arg, $match) )
		{
			return $match[1];
		}
		return false;
	}
}
?>