<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<base href="http://{php} echo $_SERVER['HTTP_HOST'].str_replace("index.php","",$_SERVER['SCRIPT_NAME']);{/php}" />

	<meta name="robots" content="noindex, follow" />
	<meta name="googlebot" content="noindex, follow" />
	
	<title>##HEADER_PAGE_TITLE## :: ##KPOWERED##</title>
	<link rel="shortcut icon" type="image/x-icon" href="./themes/favicon.ico" />
	{foreach from=$cssfiles item=cssfile}
		<link rel="stylesheet" type="text/css" href="{$base_url}{$cssfile}" media="screen" title="Normal" />
	{/foreach}
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
<![endif]-->
	<script type="text/javascript" src="{$base_url}/scripts/prototype.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/scriptaculous.js"></script>
	<!-- A better JSON implementation than prototype's one -->
	<script type="text/javascript" src="{$base_url}/scripts/json.js"></script>
    <script type="text/javascript">
	<!--
	{literal}
	/**
	 * Some Globals vars that can be useful;
	 */
	var KGlobals = {};{/literal}
	KGlobals.baseurl = "{$base_url}";
	{if $user_id}KGlobals.userID = {$user_id};{/if}
{if $islogged}{literal}
	// Auto-away system
	var lastPresenceNotification;

	function sendPresenceNotification() {
		lastPresenceNotification = new Date();
		new Ajax.Request("{/literal}{kurl app="login" page="presence"}{literal}", {method: "post"});
	}

	function presenceNotification(event) {
		//alert("Ok, you are here...");
		var newDate = new Date();
		if ((newDate - lastPresenceNotification) > 30000) {
			// 30 seconds without notifying the server of our presence.
			// He must be worrying, we must contact him !
			sendPresenceNotification();
		}
	}

	function initializeAutoAwaySystem() {
		lastPresenceNotification = new Date(); // sendPresenceNotification();
		Event.observe(document, "keydown", presenceNotification);
		Event.observe(document, "click", presenceNotification);
		Event.observe(document, "mousemove", presenceNotification);
	}

	Event.observe(window, "load", initializeAutoAwaySystem);
	{/literal}{/if}
	//-->
    </script>
	<!-- Pantie System -->
	<script type="text/javascript" src="{$base_url}/scripts/pantie.js"></script>
	<script type="text/javascript">
		pantie = new Pantie('{kurl app="header" page="pantie"}');
	</script>
	<script type="text/javascript" src="{$base_url}/scripts/karibou.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/kform.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/scal.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/hintbox.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/BigInt.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/Barrett.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/RSA.js"></script>
	<!-- For LightBox -->
	<script type="text/javascript" src="{$base_url}/scripts/yui-min.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/Klightbox.js"></script>
{hook name="html_head"}
</head>
<body>
	<div id="container">
		<h1 class="pagetitle"><a href="{kurl app="" page=""}#" id="logo"><span>Karibou</span></a></h1>
		<div id="main" class="nonav">
			<div id="content1">
				<div id="content">
{hook name="page_content_start"}
