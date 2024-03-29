<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <script type="text/javascript">
	<!--
	/**
	 * Some Globals vars that can be useful;
	 */
	var KGlobals = {literal}{}{/literal};
	KGlobals.baseurl = "{$base_url}";
	//-->
    </script>
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
	<script type="text/javascript" src="{$base_url}/scripts/karibou.js"></script>
	<script type="text/javascript" src="{$base_url}/scripts/kform.js"></script>
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
