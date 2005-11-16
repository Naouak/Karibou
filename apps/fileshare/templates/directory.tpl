<h1>##FILESHARE_TITLE##</h1>
<h3>##BROWSINGDIRECTORY## : /{$myDir->getPath()}</h3>
<div class="fileshare">

	{if ($uploadallowed)}
	<div class="helper">##FILESHARE_DESCRIPTION##</div>
	<div class="uploadlink"><a href="{kurl page="upload" directoryname=$myDir->getPathBase64()}">##UPLOAD_A_FILE##</a></div>
	<div class="createdirectorylink"><a href="{kurl page="createdirectory" directoryname=$myDir->getPathBase64()}">##CREATE_A_DIRECTORY##</a></div>
	{/if}

	{if ($myDir->exists())}
		{if !$myDir->isRootDir()}
		<div class="uponelevellink">
			<a href="{kurl page="directory" directoryname=$myDir->getParentPathBase64()}">##UPONELEVEL##</a>
		</div>
		{/if}
		{include file="list.tpl"}
	{else}
		##DIRECTORYDOESNOTEXIST##
	{/if}
</div>