<?php

$config['daemons'] = array();

define("KARIBOU_BASE_DIR",dirname(__FILE__).'/..');
define("KARIBOU_CLASS_DIR",KARIBOU_BASE_DIR.'/class');
define("KARIBOU_CONFIG_DIR",KARIBOU_BASE_DIR.'/config');
define("KARIBOU_LIB_DIR",KARIBOU_BASE_DIR.'/lib');

 
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
