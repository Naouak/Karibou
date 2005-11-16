<h1>##FILESHARE_TITLE##</h1>
<div class="fileshare">
	{if ($uploadallowed)}
	<div class="helper">##FILESHARE_DESCRIPTION##</div>
	<div class="uploadlink"><a href="{kurl page="upload"}">##UPLOAD_A_FILE##</a></div>
	{/if}
	{include file="list.tpl"}
</div>
