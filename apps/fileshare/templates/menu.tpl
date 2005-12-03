{if $page == "home"}
	<li><strong>##FILESHARE##</strong></li>
{else}
	<li><a href="{kurl}">##FILESHARE##</a></li>
{/if}
{if $page == "uploadfile"}
	<li><strong>##UPLOAD_A_FILE##</strong></li>
{elseif $page == "createdirectory"}
	<li><strong>##CREATE_A_DIRECTORY##</strong></li>
{elseif ($uploadallowed)}
	<li><a href="{kurl page='upload' directoryname=$myDir->getPathBase64()}">##UPLOAD_A_FILE##</a></li>
	<li><a href="{kurl page='createdirectory' directoryname=$myDir->getPathBase64()}">##CREATE_A_DIRECTORY##</a></li>
{/if}
