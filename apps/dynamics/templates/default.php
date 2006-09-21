<?
	header('Content-Type: text/plain');

	//Problème avec les apostrophes (double quotes) lors de l'interprétation côté javascript
	//$tab = array(array('tableau' => array('nom' => 'dat"0";}s:6ine";}s:6:"nombre";s:0:"";}i:1;s:6:"chaine";}'), 'nombre' => ''), 'chaine');
	//$vars = $this->vars;
	//$tab = array(array("tableau" => array("nom" => "dat\""), "nombre" => ""), "chaine", $vars);
	//echo serialize($tab);
	

	$tab = array();
	$tab['onlineusers'] = array();
	//var_dump($this->vars['onlineusers']);
	foreach($this->vars['onlineusers'] as $user)
	{
		$tab['onlineusers'][] = array('id' => $user['user_id'], 'displayname' => $user['object']->getDisplayName(), 'username' => $user['object']->getLogin(), 'picturepath' => $user['object']->getPicturePath());
	}
	
	echo htmlspecialchars(serialize(removeQuotesInArray($tab)));
	
	/**
	 * Remplacement des doubles quotes dans le tableau
	 */
	function removeQuotesInArray($array)
	{
		if (is_array($array))
		{
			foreach($array as &$element)
			{
				if (is_array($element))
				{
					$element = removeQuotesInArray($element);
				}
				else
				{
					$element = str_replace('"', '\'', $element);
				}
			}
		}
		else
		{
			$array = str_replace('"', '\'', $array);
		}
		return $array;
	}

?>