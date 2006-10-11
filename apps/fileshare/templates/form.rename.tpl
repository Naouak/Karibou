<h1>##FILESHARE_TITLE##</h1>
{if isset($myElement)}
<div class="fileshare">
{if $allowed}
	<div class="helper">##FS_RENAME_DESCRIPTION##</div>
	<form action="{kurl action="rename"}" method="post" class="move">
		<div class="from">			##FS_RENAME_ELEMENT## <strong>{$myElement->getSysInfos('name')}</strong>
			<input type="hidden" name="elementid" value="{$myElement->getElementId()}" />
		</div>
		<label for="name">##FS_RENAME_NEW_NAME##</label> : <input type="text" name="name" value="{$myElement->getSysInfos('name')}" />
		<br />
		<label for="name">##FS_RENAME_NEW_DESCRIPTION##</label> : <textarea name="description">{$myElement->getLastVersionInfo('description')}</textarea>
		<br />
		<input type="submit" value="##FS_RENAME_BUTTON##" class="button" />
	</form>
{else}
	<div class="hint hint_notallowed">##NOACCESS##</div>
{/if}
</div>
{else}
{/if}