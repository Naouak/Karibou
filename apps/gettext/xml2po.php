<?php
session_start();
header ('Content-Type: text/html; charset=UTF-8');
//header ('Content-Type: text/html; charset=ISO-8859-1');

if (isset($_GET['l'])) {
	define('LANG',			$_GET['l']);
} elseif (isset($_SESSION['l'])) {
	define('LANG',			$_SESSION['l']);
} else {
	define('LANG',			'fr');
}
$_SESSION['l'] = LANG;

if (isset($_GET['d'])) {
	define('DISPLAY',			$_GET['d']);
} elseif (isset($_SESSION['d'])) {
	define('DISPLAY',			$_SESSION['d']);
} else {
	define('DISPLAY',			'fr');
}
$_SESSION['d'] = DISPLAY;

if (isset($_GET['f'])) {
	define('FILE',			$_GET['f']);
} elseif (isset($_SESSION['f'])) {
	define('FILE',			$_SESSION['f']);
} else {
	define('FILE',			'fr');
}
$_SESSION['f'] = FILE;

define('PATH',			'../../apps');
define('FILENAME',			"messages");
define('FILEFULLNAME',		FILENAME.'.'.LANG.'.'.DISPLAY);
$_SESSION['FILEFULLNAME'] = FILEFULLNAME;

?>
<pre><a href="./index.php">Accueil</a> &gt; <strong>Langue : </strong><a href="?l=fr">fr</a>:<a href="?l=en">en</a> | <strong>Affichage : </strong><a href="?d=li">li</a>:<a href="?d=po">po</a>:<a href="?d=msg">msg</a>:<a href="?d=dup">dup</a> | <strong>Fichier : </strong><a href="?f=oui">oui</a>:<a href="?f=non">non</a>
Configuration actuelle : <?=LANG?> | <?=DISPLAY?> | <?=FILE?> <?php if(FILE=='oui'){ echo '('.FILEFULLNAME.')';};?>
<hr />
<?php

$GLOBALS['keys']		= array();
$GLOBALS['duplicates']	= array();

/**
 * Execution
 */
//Si un fichier est déterminé, on commence l'enregistrement dans le buffer
if (FILE == 'oui') {
	ob_start();
}

do_dir_global(PATH);
if (DISPLAY == 'li' || DISPLAY == 'dup') {
	foreach ($GLOBALS['keys'] as $key => $sentences) {
		$snum = count($sentences);

		$previous = "";
		$same = TRUE;
		$lines = "";
		foreach($sentences as $id => $sentence) {
			if ($previous == "") {
				$previous = $sentence[1];
				$previouss = $sentence[2];
			}

			if ($previous != $sentence[1]) {
				$same = FALSE;
			}
			
			$lines .= "<li>".fs($sentence[1]);
			if ($snum > 1) {
				$lines .= "<span style=\"color: #efefef; font-style:italic;\"> (".$sentence[2].")</span>";
			}
			$lines .= "</li>";
		}
		if (DISPLAY == 'li' || (DISPLAY == 'dup' && $same === FALSE)) {
			echo "<ul><li><strong><a href=\"find_translations_in_tpl.php?key=$key\">".$key."</a></strong></li>";
			if ($snum > 1) {
				if (!$same) {
					echo "<ul style=\"background-color: red;\">";
				} else {
					echo "<ul style=\"background-color: blue;\">";
				}
			} else {
				echo "<ul>";
			}
			echo $lines;
			echo "</ul></ul>";
		}
	}
}

if (DISPLAY == 'po') {
?>
msgid ""
msgstr ""
"Project-Id-Version: 1\n"
"Report-Msgid-Bugs-To: team@karibou.org\n"
"POT-Creation-Date: 2006-07-24 16:54+0200\n"
"PO-Revision-Date: 2006-07-24 16:54+0200\n"
"Last-Translator: Karibou Team <team@karibou.org>\n"
"Language-Team: Karibou Team <team@karibou.org>\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: UTF-8\n"

<?php
	//On ne prend pas en compte les duplicates
	foreach ($GLOBALS['keys'] as $key => $sentences) {
		echo "msgid \"".$key."\"\n";
		echo "msgstr \"".fs($sentences[0][1])."\"\n\n";
		/*
		foreach($sentences as $id => $sentence) {
			//echo "msgid \"".$sentence['key']."\"\r\n";
			echo "msgstr \"".fs($sentence[1])."\"\r\n\r\n";
		}
		*/
	}
}
//Si un fichier est déterminé, on commence l'enregistrement dans le buffer
if (FILE == 'oui') {
	$buffer = ob_get_clean();
	file_put_contents(FILEFULLNAME, $buffer);
	echo "Traductions enregistr&eacute;es dans le fichier <strong>".FILEFULLNAME."</strong>.\n";
	echo "<a href=\"msgfmt.php\">&gt; &gt; &gt; Compiler ce fichier</a>";
}


//Création du fichier des dupliqués (traductions globales)
	if (!is_dir(PATH.'/_languages')) {
		mkdir(PATH.'/_languages');
	}
file_put_contents(PATH.'/_languages/global.'.LANG, serialize($GLOBALS["keys"]));


/**
 * Fonctions
 */
function do_file_global($file)
{
	echo (DISPLAY == 'msg')?"#Fichier de langue : $file ":'';
	$xml = simplexml_load_file($file);
	echo (DISPLAY == 'msg')?" (".count($xml->sentence)." traductions)\r\n":'';
	foreach($xml->sentence as $sentence) {
		foreach($sentence->translation as $trans) {
			if ($trans['language'] == LANG) {
				$attribute = FALSE;
				
				//Recherche de la clé
				foreach($sentence->attributes() as $attribute_key => $attribute_object) {
					if ($attribute_key == 'key') {
						$attribute = substr($attribute_object,0);
					}
				}
				
				//Vérification que la clé n'est pas dupliquée
				if (array_key_exists($attribute, $GLOBALS['keys'])) {
					if (DISPLAY == 'dupkill') {
						echo "<hr />";
						echo "<strong>$attribute</strong> : ";
						echo "<em>UNE ENTREE DUPLIQUEE A ETE TROUVEE</em>\n";
						echo $file."\n";
						var_dump($sentence);
						echo "\n^^^^^^^^^^^^^^^^^^^^\n";
						echo $GLOBALS['keys'][$attribute][2]."\n";
						var_dump($GLOBALS['keys'][$attribute])."\n";
						//var_dump($GLOBALS['keys']);
						echo "<hr />";
					}
					/*
					if (!isset($GLOBALS['duplicates'][$attribute]) || !is_array($GLOBALS['duplicates'][$attribute])) {
						$GLOBALS['duplicates'][$attribute] = array();
						$GLOBALS['duplicates'][$attribute][] = array ($GLOBALS['keys'][$attribute][0][2], $GLOBALS['keys'][$attribute][0][1]);
					}
					$GLOBALS['duplicates'][$attribute][] = array ($file, $trans);
					*/
				}
				
				if (!isset($GLOBALS['keys'][$attribute])) {
					$GLOBALS['keys'][$attribute] = array();
				}
				
				$msgstr = fs($trans);
				if ($msgstr != '') {
					$trans = fs($trans);
				} else {
					$trans = fs($trans['value']);
				}
					
				$GLOBALS['keys'][$attribute][] = array(LANG, $trans, $file);
				
				//Affichage des lignes de traduction
				if (DISPLAY == 'msg') {
					echo "msgid \"".$sentence['key']."\"\r\n";
					echo "msgstr \"".fs($trans)."\"\r\n\r\n";
				}
				
			}
		}
	}

}


function fs($str)
{
//var_dump(ord(utf8_encode('à')));

	//$str = str_replace('/'.chr(195).'/', '@AAA@', $str);
	$str = preg_replace('/^([\s]+)/', '', $str);
	$str = preg_replace('/([ ]+)/', ' ', $str);
	
	//Supprime les espaces de fin de chaîne en évitant de poser problème pour les caractères spéciaux
	//(comme le "à" qui doit, sur deux caractères; utf8, être composé sur le dernier octet d'un code similaire à l'espace)
	$str = preg_replace('/([\s]{2,999})$/', '', $str);
	$str = preg_replace('/([\n]+)/', ' ', $str);
	$str = preg_replace('/([\t]+)/', ' ', $str);

	$str = stripslashes($str);
	$str = str_replace('"', '\"', $str);

	//$str = preg_replace('/@AAA@/', 'à', $str);

	return $str;
}


// go through a directory
function do_dir_global($dir)
{
	$d = dir($dir);

	while (false !== ($entrysingle = $d->read())) {
		if ($entrysingle == '.' || $entrysingle == '..' || preg_match('/svn/', $entrysingle)) {
			continue;
		}
		$entry = $dir.'/'.$entrysingle;
		if ($entrysingle == 'languages.xml')  {
			$pi = pathinfo($entry);
			do_file_global($entry);
		} elseif (is_dir($entry)) {
			do_dir_global($entry);
		}
	}

	$d->close();
}

/*
?>
msgid ""
msgstr ""
"Project-Id-Version: 1\n"
"Report-Msgid-Bugs-To: team@karibou.org\n"
"POT-Creation-Date: 2006-07-24 16:54+0200\n"
"PO-Revision-Date: 2006-07-24 16:54+0200\n"
"Last-Translator: Karibou Team <team@karibou.org>\n"
"Language-Team: Karibou Team <team@karibou.org>\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: UTF-8\n"
<?
*/

?>