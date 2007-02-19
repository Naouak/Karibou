<?php
	$checklist = array();
	/**
	 * Liste des fonctions nécessaires
	 */
	$checklist[] = array(
		'type'				=> 'function',
		'name'				=> 'imap_open',
		'errortitle'		=> 'Le module IMAP pour PHP n\'est pas charg&eacute;',
		'errordescription'	=> 'Le module IMAP pour PHP est n&eacute;cessaire pour le bon fonctionnement de l\'application <em>mail</em> de <a href="http://www.karibou.org" title="Karibou : L\'Intranet">Karibou</a>.',
		'resolve'			=> '
					Vous devez ajouter la ligne 
					<pre>extension=php_imap.dll</pre>
					dans votre fichier <strong><em>php.ini</em></strong> si vous &ecirc;tes sous <em>Windows</em>.'
		);
	
	/**
	 * Liste des extensions nécessaires
	 */
	$checklist[] = array(
		'type'				=> 'extension',
		'name'				=> 'PDO',
		'errortitle'		=> 'Le module PDO pour PHP n\'est pas charg&eacute;',
		'errordescription'	=> 'Le module PDO pour PHP est n&eacute;cessaire pour le bon fonctionnement de <a href="http://www.karibou.org" title="Karibou : L\'Intranet">Karibou</a>.',
		'resolve'			=> '
					Vous devez ajouter la ligne 
					<pre>extension=php_pdo.dll</pre>
					dans votre fichier <strong><em>php.ini</em></strong> si vous &ecirc;tes sous <em>Windows</em>.'
		);
	$checklist[] = array(
		'type'				=> 'extension',
		'name'				=> 'pdo_mysql',
		'errortitle'		=> 'Le module PDO MySQL pour PHP n\'est pas charg&eacute;',
		'errordescription'	=> 'Le module PDO MySQL pour PHP est n&eacute;cessaire pour le bon fonctionnement de <a href="http://www.karibou.org" title="Karibou : L\'Intranet">Karibou</a>.',
		'resolve'			=> '
					Vous devez ajouter la ligne 
					<pre>extension=php_pdo_mysql.dll</pre>
					dans votre fichier <strong><em>php.ini</em></strong> si vous &ecirc;tes sous <em>Windows</em>.'
		);
	
	$error = 0;
  $errormessages = "";
	
	//var_dump(get_loaded_extensions());
	
	foreach ($checklist as $check)
	{
		if ($check['type'] == 'function' || $check['type'] == 'extension')
		{
			//$error = ($check['type'] == 'function')?function_exists($check['name']):extension_loaded($check['name']);
			if ($check['type'] == 'function')
			{
				$exist = function_exists($check['name']);
			}
			else
			{
				$exist = extension_loaded($check['name']);
			}
			if ( !$exist )
			{
				$error++;
				
				$start = ($check['type']=='function')?'La fonction':'L\'extension';
				
				$errormessages .= "
					<h2>".$check['errortitle']."</h2>
					".$check['errordescription']."
					<h3>Comment faire ?</h3>
					".$check['resolve']."
					<h3>Description technique</h3>
					".$start." <a href=\"http://www.php.net/".$check['name']."\" title=\"Documentation en ligne de la fonction ".$check['name']."\"><strong>".$check['name']."</strong></a> n'existe pas.
					";
			}
		}
	}


	if( !is_file(dirname(__FILE__).'/config.php') )
	{
		die("Must create a config File.");
	}

	if ($error>0) {
		echo "<h1>$error erreurs".(($error>1)?'s':'')." rencontr&eacute;e".(($error>1)?'s':'')."</h1>";
		echo $errormessages;
		die();
	}
?>
