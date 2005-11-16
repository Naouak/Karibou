<?php

$config['txt_title']    = 'Da K4ar1b0u !';
$config['session_name'] = '_KARIBOU_';

$config['site']['base_url'] = 'http://'.$_SERVER["SERVER_NAME"].'/';
$config['base_url'] = '/';

$config['bdd']['frameworkdb'] = "##DBDEFAULT##";
$config['bdd']['annuairedb'] = "##DBANNUAIRE##";
// for mysql dsn is like : mysql:dbname=karibou;host=localhost
$config['bdd']['dsn']    = 'mysql:dbname='.$config['bdd']['frameworkdb'].';host=localhost';
$config['bdd']['username']    = '##DBUSER##';
$config['bdd']['password']    = '##DBPASS##';

//$config['login']['backend'] = "mysql";
$config['login']['backend'] = "imap";
$config['login']['server'] = "mail.telecomlille.net";
//$config['login']['server_username'] = "";
//$config['login']['server_password'] = "";
$config['login']['server_schema'] = "INBOX";
$config['login']['server_options'] = "/imap/tls/novalidate-cert";
$config['login']['post_username'] = "@telecomlille.net";
$config['login']['createuser'] = TRUE;
$config['login']['savepassword'] = TRUE;


$config['keychain']['storage'] = 'session';


$config['ldap']['rdn'] = '##LDAPRDN##';
$config['ldap']['jvd'] = '##LDAPJVD##';
$config['ldap']['pwd'] = '##LDAPPWD##';

/* VAR DEFINITION */

define("KARIBOU_DIR", dirname(__FILE__).'/../karibou2');
define("KARIBOU_COMPILE_DIR", dirname(__FILE__).'/../compile');
define("KARIBOU_CACHE_DIR", dirname(__FILE__).'/../cache');
define("KARIBOU_APP_DIR", dirname(__FILE__).'/../apps');
define("KARIBOU_DAEMON_DIR", dirname(__FILE__).'/../daemons');
define("KARIBOU_PUB_DIR", dirname(__FILE__).'/pub');
define("KARIBOU_THEMES_URL", "/themes");

/* STYLES */

$config['style'][0]['titre']        = "karibou";
$config['style'][0]['fichier_css']    = KARIBOU_THEMES_URL."/karibou/default.css";
$config['style'][0]['home_css']    = KARIBOU_THEMES_URL."/karibou/default.css";

$config['style'][1]['titre']        = "default";
$config['style'][1]['fichier_css']    = KARIBOU_THEMES_URL."/default/greu.css";
$config['style'][1]['home_css']    = KARIBOU_THEMES_URL."/default/home.css";
$config['style'][1]['fichier_js']    = KARIBOU_THEMES_URL."/menu_mcben.js";

?>
