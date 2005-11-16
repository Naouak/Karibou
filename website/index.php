<?php

require_once dirname(__FILE__).'/config.php';

require_once KARIBOU_DIR.'/class/karibou.class.php';

// en prod on supprime le reporting d'erreurs
Debug::$display = true;
//error_reporting(E_STRICT);
error_reporting(E_ALL);

$karibou = new Karibou();

$karibou->loadAppDir(KARIBOU_APP_DIR, "config.xml");

$karibou->build();

$karibou->display();

unset($karibou);
$timer = ExecutionTimer::getRef();
Debug::display($timer->getHTML());
Debug::flushMessages();

?>
