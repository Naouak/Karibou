<?php
session_start();
header ('Content-Type: text/html; charset=UTF-8');
//header ('Content-Type: text/html; charset=ISO-8859-1');

/**
 * Configuration
 */
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

?>
<pre><a href="./index.php">Accueil</a> &gt; <strong>Langue : </strong><a href="?l=fr">fr</a>:<a href="?l=en">en</a> | <strong>Affichage : </strong><a href="?d=li">li</a>:<a href="?d=po">po</a>:<a href="?d=msg">msg</a>:<a href="?d=dup">dup</a> | <strong>Fichier : </strong><a href="?f=oui">oui</a>:<a href="?f=non">non</a>
Configuration actuelle : <?=LANG?> | <?=DISPLAY?> | <?=FILE?> <?php if(FILE=='oui'){ echo '('.FILEFULLNAME.')';};?>
<hr />
<?php


$GLOBALS['globalised']	= array();

//FORCE_REPLACE force le remplacement des fichiers
define('FORCE_REPLACE', TRUE);
define('PATH', '../../apps');
$keyfile = PATH.'/_languages/global.'.LANG;
if (!is_file($keyfile)) die("Pas de fichier cl&eacute; ($keyfile).");
$GLOBALS['keys'] = unserialize(file_get_contents($keyfile));
//Création initiale du fichier
ob_start();
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
$buffer = ob_get_clean();
$po_file_from_xml = PATH.'/_languages/'.LANG.'.po';
file_put_contents($po_file_from_xml, $buffer);
unset($po_file_from_xml);



/**
 * Execution
 */
echo "<pre>";
do_dir(PATH);

/**
 * Fonctions
 */
function do_file($file, $dir, $lang)
{
	//Créer répertoire (si inexistant)
	$lang_dir = $dir.'/languages';
	if (!is_dir($dir.'/languages')) {
		mkdir($dir.'/languages');
	}
	
	if (!is_dir(PATH.'/_languages')) {
		mkdir(PATH.'/_languages');
	}
	
	if (!is_file($lang_dir.'/'.$lang) || FORCE_REPLACE) {
		$translations = get_translations_from_xml($file);
		
		//Créer le fichier de traductions globales
		foreach ($translations as $key => $msg) {
			ob_start();
			if (count($GLOBALS['keys'][$key]) > 1 && !in_array($key,$GLOBALS['globalised'])) {
				//Il y a un duplicate sur cette clé
				echo "msgid \"".$key."\"\n";
				echo "msgstr \"".fs($msg)."\"\n\n";
				$GLOBALS['globalised'][] = $key;
			}
			$buffer = ob_get_clean();
			$po_file_from_xml = PATH.'/_languages/'.$lang.'.po';
			file_put_contents($po_file_from_xml, $buffer, FILE_APPEND);
		}
		if (isset($po_file_from_xml))
			echo "\n\n -> Traductions <strong>GLOBALES</strong> enregistr&eacute;es dans le fichier <strong>".$po_file_from_xml."</strong>.";
		
		
		//Créer fichier .po à partir du XML (si po inexistant et xml existant)
		ob_start();
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
		foreach($translations as $key => $msg) {
			//Retourne la traduction si celle-ci n'est pas déjà dans le fichier des traductions globales.
			if (count($GLOBALS['keys'][$key]) <= 1) {
				echo "msgid \"".$key."\"\n";
				echo "msgstr \"".fs($msg)."\"\n\n";
			}
		}
		$buffer = ob_get_clean();
		$po_file_from_xml = $lang_dir.'/'.$lang.'.po';
		file_put_contents($po_file_from_xml, $buffer);
		echo "\nTraductions enregistr&eacute;es dans le fichier <strong>".$po_file_from_xml."</strong>.";
	}
}

function get_translations_from_xml($file) {
		//Import à partir du fichier XML
		//Lire language.xml
		echo (DISPLAY == 'msg')?"#Fichier de langue : $file ":'';
		$xml = simplexml_load_file($file);
		echo (DISPLAY == 'msg')?" (".count($xml->sentence)." traductions)\r\n":'';
		
		$translations = array();
		foreach($xml->sentence as $sentence) {
			foreach($sentence->translation as $trans) {
				//Recherche de la clé
				foreach($sentence->attributes() as $attribute_key => $attribute_object) {
					if ($trans['language'] == LANG) {
						if ($attribute_key == 'key') {
							$attribute = substr($attribute_object,0);
						}
						
						$msgstr = fs($trans);
						if ($msgstr != '') {
							$trans = fs($trans);
						} else {
							$trans = fs($trans['value']);
						}
						$translations[substr($sentence['key'],0)] = $trans;
					}
				}
			}
		}
		return $translations;
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
function do_dir($dir)
{
	$d = dir($dir);

	while (false !== ($entrysingle = $d->read())) {
		if ($entrysingle == '.' || $entrysingle == '..' || preg_match('/svn/', $entrysingle)) {
			continue;
		}
		$entry = $dir.'/'.$entrysingle;
		if ($entrysingle == 'languages.xml')  {
			do_file($entry, $dir, LANG);
		} elseif (is_dir($entry)) {
			do_dir($entry);
		}
	}

	$d->close();
}

?>