{if $page == "home"}
	<li><strong>##FILESHARE##</strong></li>
{else}
	<li><a href="{kurl}">##FILESHARE##</a></li>
{/if}
{if $page == "uploadfile"}
	<li><strong>##UPLOAD_A_FILE##</strong></li>
{elseif $page == "createdirectory"}
	<li><strong>##CREATE_A_DIRECTORY##</strong></li>
{elseif ($canWrite) && $folderExistsInDB}
	<li><a href="{kurl page='upload' directoryname=$myDirPathBase64}">##UPLOAD_A_FILE##</a></li>
	<li><a href="{kurl page='createdirectory' directoryname=$myDirPathBase64}">##CREATE_A_DIRECTORY##</a></li>
	{if ($elementid > 0)}
	<li><a href="{kurl page="renameform" elementid=$elementid}">##FS_RENAME_DIRECTORY_SHORT##</a></li>
	{/if}
{/if}