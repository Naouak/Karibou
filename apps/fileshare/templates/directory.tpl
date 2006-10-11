<h1>##FILESHARE_TITLE##</h1>
<h3>##BROWSINGDIRECTORY## : /{$myDir->getPath()}</h3>
<div class="fileshare">

	{*if ($uploadallowed)}
	<div class="helper">##FILESHARE_DESCRIPTION##</div>
	{/if*}

	{if ($myDir->exists())}
		{include file="list.tpl"}
	{else}
		##DIRECTORYDOESNOTEXIST##
	{/if}
</div>