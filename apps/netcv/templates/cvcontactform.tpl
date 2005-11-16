<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="{kurl page="cvskindisplay" filename="messages.css"}" media="screen" title="Normal" />
		<title>Contact</title>
		<meta name="robots" content="noindex, nofollow" />
	</head>
	<body>
		<div class="message">
			<h1>Formulaire de contact</h1>
			{include file="messages.tpl"}
			<form method="POST" action="{kurl page="cvcontactformsend"}">
				<input type="hidden" name="hostname" value="{$hostname}"/>
				<input type="hidden" name="lang" value="{$lang}"/>
				<label for="from">From (email): </label><input type="text" id="from" name="from" value="{$from|escape:"html"}"/><br />
				<label for="subject">Subject: </label><input type="text" id="subject" name="subject" value="{$subject|escape:"html"}"/><br />
				<label for="message">Message: </label><textarea id="message" name="message" cols="40" rows="10" />{$message|escape:"html"}</textarea><br />
				<input type="submit" value="Send" class="submit">
			</form>
		</div>
	</body>
</html>
