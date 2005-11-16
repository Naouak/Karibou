<?php

/**
 * Liste des constantes
 *
 * @version $Id: constantes.cst.php,v 1.8 2004/08/21 09:55:12 jon Exp $
 * @copyright 2003 
 **/

define("_DEBUG_",1);
 
define("KARIBOU_BASE_DIR",dirname(__FILE__).'/..');
define("KARIBOU_CLASS_DIR",KARIBOU_BASE_DIR.'/class');
define("KARIBOU_CONFIG_DIR",KARIBOU_BASE_DIR.'/config');
define("KARIBOU_LIB_DIR",KARIBOU_BASE_DIR.'/lib');

 
/*
* Constantes pour l instanciation des applis
*/
 define("_MINI_", 1);
 define("_BIG_", 2);
 define("_POPUP_", 4);
/*
* Constantes d'applis
*/

/*
* Actuellement sur Karibou

1  	Signets  	
2 	Niouzes 	
3 	Calendrier 	
4 	ToDo
5 	Météo
6 	Mail 	
7 	Search engine 	
8 	Annuaire 	
10 	Lez Infos 	
11 	Mini Chat 	
12 	Le Gros Chat 	
14  FLASHMAILS
*/

define("_APPLI_APPLIVIDE_", 0);
define("_APPLI_CONFIG_", 1);
define("_APPLI_NEWS_", 2);	//Ajout par DaT pour l'appli News
define("_APPLI_MAIL_", 6);
define("_APPLI_LEZINFOS_",7);
define("_APPLI_LANNUAIRE_", 8);
define("_APPLI_PHOTO_", 9);
define("_APPLI_MINICHAT_", 11);
define("_APPLI_FLASHMAIL_", 14);
define("_APPLI_SONDAGE_",15);
define("_APPLI_VISITEURS_",16);
define("_APPLI_CITATION_", 17);
define("_APPLI_PERMISSIONSMANAGEMENT_", 18);

define("_APPLI_TEST_", 13);



/*
*  Constantes gérant les droits sur les applis
*  Le droit n implique les droits m < n
*  ex: SELF_WRITE implique READ
*  
*/
  
define("_UNKNOWN_", -1);

/**
 * Pas d'instanciation autorisée
 */
define("_NO_ACCESS_", 0);

/**
 * Droit de lecture
 */
define("_READ_ONLY_", 1);

/**
 * Les permissions par défaut s appliquent (cas d un user non loggé)
 */
define("_DEFAULT_", 2);

/**
 * Droit de lecture + écriture sur données personnelles (ex: une news que j'ai postée)
 */
define("_SELF_WRITE_", 3);

/**
 * Droit de lecture + écriture sur données du groupe (ex: une news du groupe postée par un membre du groupe)
 */
define("_GROUP_WRITE_", 4);

/**
 * Droit d'administration sur un groupe (création, modification, suppression d'utilisateurs...)
 */
define("_GROUP_ADMIN_", 5);

/**
 * Droit de lecture + écriture totale (groupe intranet par exemple)
 */
define("_FULL_WRITE_", 20);

/**
 * Power User
 */
define("_ADMIN_", 99);

?>
