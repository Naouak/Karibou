<h1>##FILESHARE_TITLE##</h1>
{if isset($myElement)}
<div class="fileshare">
	<div class="helper">##FS_RENAME_DESCRIPTION##</div>
	<form action="{kurl action="rename"}" method="post" class="move">
		<div class="from">
			<input type="hidden" name="elementid" value="{$myElement->getElementId()}" />
		</div>
		<label for="name">##FS_RENAME_NEW_NAME##</label> : <input type="text" name="name" value="{$myElement->getSysInfos('name')}" />
		<br />
		<label for="name">##FS_RENAME_NEW_DESCRIPTION##</label> : <textarea name="description">{$myElement->getLastVersionInfo('description')}</textarea>
		<br />
		<input type="submit" value="##FS_RENAME_BUTTON##" class="button" />
	</form>
</div>
{/if}