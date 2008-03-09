
<body onload="LoadSiteNavigation();" onKeypress="keyCheck(event);">
	<? /* HintBox support */ ?>
	<div id="hintbox">&nbsp;</div>
	<script type="text/javascript" src="/themes/js/hintbox.js"></script>

	<div id="container">
		<a href="<?=kurl(array('app'=>'', 'page'=>''));?>#" id="logo"><span><h1>Karibou</h1></span></a>
		<div id="main" class="nonav">
			<div id="content1">
				<div id="content">
<? hook(array('name'=>"page_content_start")); ?>
