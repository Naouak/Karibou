<?php

include('check.php');

require_once dirname(__FILE__).'/config.php';
require_once KARIBOU_DIR.'/class/karibou.class.php';
$timer = ExecutionTimer::getRef();

// en prod on supprime le reporting d'erreurs
Debug::$display = true;
//error_reporting(E_STRICT);
error_reporting(E_ALL);
ini_set("display_error",1);

$timer->start("Full Load");
$karibou = new Karibou();

$karibou->loadAppDir(KARIBOU_APP_DIR, "config.xml");

$karibou->build();

$karibou->display();

$timer->stop("Full Load");

unset($karibou);
//Debug::display($timer->getTableHTML());

Debug::display($timer->getText());
Debug::flushMessages();

?>