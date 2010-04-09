<?php
define("PANTIE_ROOT", dirname(__FILE__) . "/../karibou2/lib/pantie");


// ---
// Inclusions
// ---

require_once "config.php";
require_once PANTIE_ROOT . "/Pantie.class.php";
require_once PANTIE_ROOT . "/PantieEvent.class.php";
require_once PANTIE_ROOT . "/PantieSocketException.class.php";
require_once PANTIE_ROOT . "/PantieSocket.class.php";
require_once PANTIE_ROOT . "/PantieToolkit.class.php";
require_once KARIBOU_DIR . "/class/kpantie.class.php";

// ---
// Code itself
// ---

// Configuring the headers
header("Content-Type: application/json; charset=utf-8");

// Getting parameters
$session = filter_input(INPUT_POST, "session", FILTER_SANITIZE_STRING);

if(isset($_POST['events']) and is_array($_POST['events'])) {
	$events = $_POST['events'];
} else {
	$events = array();
}

if(empty($session)) {
	exit("we're not going very far with this...");
}

// Waiting the event
try {
	$p = new KPantie($session);
	$data = $p->waitEvent($events);
} catch(PantieSocketTimeout $e) {
	$data = new stdClass();
	$data->timeout = true;
} catch(Exception $e) {
	$data = new stdClass();
	$data->error = true;
}

echo json_encode($data);
