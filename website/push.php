<?php
define("PANTIE_ROOT", dirname(__FILE__) . "/../karibou2/lib/pantie");

// ---
// Configuration
// ---

// ---
// Inclusions
// ---

require_once PANTIE_ROOT . "/Pantie.class.php";
require_once PANTIE_ROOT . "/PantieEvent.class.php";
require_once PANTIE_ROOT . "/PantieSocketException.class.php";
require_once PANTIE_ROOT . "/PantieSocket.class.php";
require_once PANTIE_ROOT . "/PantieToolkit.class.php";

// ---
// Code itself
// ---

// Configuring the headers
header("Content-Type: application/json; charset=utf-8");

// Getting parameters
$session = filter_input(INPUT_POST, "session", FILTER_SANITIZE_STRING);
$events = $_POST['events'];

if(empty($session) or empty($events)) {
	exit("we're not going very far with this...");
}

// Waiting the event
try {
	$p = new Pantie($session);
	$data = $p->waitEvent($events);
} catch(PantieSocketTimeout $e) {
	$data = new stdClass();
	$data->timeout = true;
} catch(Exception $e) {
	$data = new stdClass();
	$data->error = true;
}

echo json_encode($data);
