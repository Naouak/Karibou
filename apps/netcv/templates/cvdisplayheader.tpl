<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$myNetCVSingleCV->getInfo("lang")}" xml:lang="{$myNetCVSingleCV->getInfo("lang")}">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>{$myNetCVPersonalInfo.firstname} {$myNetCVPersonalInfo.lastname} - {$myNetCVPersonalInfo.jobtitle}</title>

		<link rel="stylesheet" type="text/css" href="{kurl page="cvskindisplay" filename=$myNetCVGroup->getInfo("skin_filename")}" media="screen" title="Normal" />
		<link rel="alternate stylesheet" type="text/css" href="{kurl page="cvskindisplay" filename=$myNetCVGroup->getInfo("skin_filename")}" media="screen" title="default" />


		{if ($myNetCVGroup->getInfo('diffusion') == 'nocrawl')}
		<meta name="robots" content="noindex, nofollow" />
		{else}
		<meta name="robots" content="index, follow" />
		{/if}
		<meta name="rating" content="General" />
		<meta name="distribution" content="Global" />
		<meta name="author" content="{$myNetCVPersonalInfo.firstname} {$myNetCVPersonalInfo.lastname}" />

		<meta name="owner" content="{$myNetCVPersonalInfo.firstname} {$myNetCVPersonalInfo.lastname}" />
		<meta name="description" content="{$myNetCVPersonalInfo.firstname} {$myNetCVPersonalInfo.jobtitle}" />
		<meta name="keywords" content="curriculum vitae, CV, CVs, curriculum vitaes, curriculums, vitaes, en ligne, curriculum vitae en ligne, CV en ligne, CVs en ligne, internet, creation de CV" />
		<meta name="copyright" content="{$myNetCVPersonalInfo.firstname} {$myNetCVPersonalInfo.lastname}" />
		<meta name="revisit-after" content="21 days" />
	</head>

	<body>
