<?php

	$checklist = array();
	/**
	 * Liste des fonctions nécessaires
	 */

	/* No more check is made on the functions... But the type "function"
	   still exists, so keep that in mind for new features. */

	/**
	 * Liste des fichiers nécessaires
	 */
	
	$checklist[] = array(
		'type'			=> 'file',
		'name'			=> KARIBOU_SMARTY_DIR . '/Smarty.class.php',
		'errortitle'		=> 'Smarty est introuvable',
		'errordescription'	=> 'Karibou utilise <a href="http://www.smarty.net/">Smarty</a> comme moteur de template. Cependant, il est impossible de trouver Smarty. ',
		'resolve'		=> 'Vous devez installer Smarty dans le <em>include_path</em> de PHP. Sous Debian, la commande <br /><pre>aptitude install smarty</pre> devrait régler le problème.'
	);

	$checklist[] = array(
		'type'			=> 'file',
		'name'			=> 'PEAR.php',
		'errortitle'		=> 'PEAR est introuvable',
		'errordescription'	=> 'Karibou utilise des classes en provnenance de <a href="http://pear.php.net/">PEAR</a>. Cependant, il est impossible de trouver PEAR.',
		'resolve'		=> 'Vous devez installer PEAR dans le <em>include_path</em> de PHP. Sous Debian, la commande <br /><pre>aptitude install php-pear</pre> devrait régler le problème.'
	);

	$checklist[] = array(
		'type'			=> 'file',
		'name'			=> 'Crypt/RSA.php',
		'errortitle'		=> 'CryptRSA est introuvable',
		'errordescription'	=> 'Certaines données sont chiffrées en utilisant la classe CryptRSA de PEAR. Cependant, il semblerait qu\'elle ne soit pas actuellement installée.',
		'resolve'		=> 'Vous devez installer CryptRSA dans le <em>include_path</em> de PHP. Si PEAR est correctement, la commande <br /><pre>pear install Crypt_RSA-1.2.1</pre> devrait régler le problème.'
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
	$checklist[] = array(
		'type'				=> 'extension',
		'name'				=> 'gettext',
		'errortitle'		=> 'Le module GetText pour PHP n\'est pas charg&eacute;',
		'errordescription'	=> 'Le module GetText pour PHP est n&eacute;cessaire pour le bon fonctionnement de <a href="http://www.karibou.org" title="Karibou : L\'Intranet">Karibou</a>.',
		'resolve'			=> '
					Vous devez ajouter la ligne 
					<pre>extension=php_gettext.dll</pre>
					dans votre fichier <strong><em>php.ini</em></strong> si vous &ecirc;tes sous <em>Windows</em>.'
		);

	$checklist[] = array(
		'type'				=> 'directory',
		'name'				=> KARIBOU_CACHE_DIR,
		'errortitle'		=> 'Le r&eacute;pertoire de cache ('.KARIBOU_CACHE_DIR.') n\'est pas cr&eacute;&eacute;',
		'errordescription'	=> 'Ce r&eacute;pertoire est n&eacute;cessaire pour le fonctionnement de <a href="http://www.karibou.org" title="Karibou : L\'Intranet">Karibou</a>. Son r&ocirc;le est de stocker les fichiers de cache XML g&eacute;n&eacute;r&eacute;s pour acc&eacute;l&eacute;rer le chargement.',
		'resolve'			=> '
					Vous devez cr&eacute;er le r&eacute;pertoire 
					<pre>'.KARIBOU_CACHE_DIR.'</pre>
					ou modifier le chemin dans le fichier <strong><em>website/config.php</em></strong>.'
		);
	$checklist[] = array(
		'type'				=> 'directory',
		'name'				=> KARIBOU_COMPILE_DIR,
		'errortitle'		=> 'Le r&eacute;pertoire de compilation ('.KARIBOU_COMPILE_DIR.') n\'est pas cr&eacute;&eacute;',
		'errordescription'	=> 'Ce r&eacute;pertoire est n&eacute;cessaire pour le fonctionnement de <a href="http://www.karibou.org" title="Karibou : L\'Intranet">Karibou</a>. Son r&ocirc;le est de stocker les fichiers templates Smarty (mod&egrave;les d\'affichage) compil&eacute;s pour acc&eacute;l&eacute;rer le chargement.',
		'resolve'			=> '
					Vous devez cr&eacute;er le r&eacute;pertoire 
					<pre>'.KARIBOU_COMPILE_DIR.'</pre>
					ou modifier le chemin dans le fichier <strong><em>website/config.php</em></strong>.'
		);

	$error = 0;
  $errormessages = "";
	
	//var_dump(get_loaded_extensions());
	
	foreach ($checklist as $check)
	{
		if ($check['type'] != '')
		{
			//$error = ($check['type'] == 'function')?function_exists($check['name']):extension_loaded($check['name']);
			if ($check['type'] == 'function')
			{
				$exist = function_exists($check['name']);
			}
			elseif ($check['type'] == 'extension')
			{
				$exist = extension_loaded($check['name']);
			}
			elseif ($check['type'] == 'directory')
			{
				$exist = is_dir($check['name']);
			}
			elseif ($check['type'] == 'file')
			{
				$exist = is_readable_ext($check['name']);
			}

			if ( !$exist )
			{
				$error++;
				
				
				$errormessages .= "<div class=\"error\">
					<h2>".$check['errortitle']."</h2>
					".$check['errordescription']."
					<h3>Comment faire ?</h3>
					".$check['resolve']."";
          
        if ($check['type']=='function') {
          $errormessages .="<h3>Description technique</h3>
					La fonction <a href=\"http://www.php.net/".$check['name']."\" title=\"Documentation en ligne de la fonction ".$check['name']."\"><strong>".$check['name']."</strong></a> n'existe pas.";
        } elseif ($check['type']=='extension') {
          $errormessages .="<h3>Description technique</h3>
					L'extension <a href=\"http://www.php.net/".$check['name']."\" title=\"Documentation en ligne de l'extension ".$check['name']."\"><strong>".$check['name']."</strong></a> n'existe pas.";
        }
        $errormessages .="</div>";
			}
		}
	}


	if( !is_file(dirname(__FILE__).'/config.php') )
	{
		die("Must create a config File.");
	}

	if ($error>0) {
    echo '
    <style>
      div.error {
        border: 1px solid #a89;
        margin: 1em;
        background-color: #fee;
        padding: 1em;
      }
    </style>
    ';
		echo "<h1>$error erreur".(($error>1)?'s':'')." rencontr&eacute;e".(($error>1)?'s':'')."</h1>";
		echo $errormessages;
		die();
	}

function is_readable_ext($file) {
	if (is_readable($file))
		return true;

	$dirs = explode(':', ini_get('include_path'));

	foreach($dirs as $dir) {
		if(is_readable("$dir/$file")) {
			return true;
		}
	}

	return false;
}
?>
