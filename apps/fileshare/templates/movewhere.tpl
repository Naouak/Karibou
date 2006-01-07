<h1>##FILESHARE_TITLE##</h1>
{if isset($myFile)}
<div class="fileshare">
	<div class="helper">##MOVE_DESCRIPTION##</div>
	<form action="{kurl action="move"}" method="post" class="move">
		<div class="from">			##MOVING_ELEMENT##  <strong>{$myFile->getName()}</strong>
			<input type="hidden" name="elementid" value="{$myFile->getElementId()}" />
		</div>
		<div class="to">
			##MOVING_TO## :
				<select name="destinationid">
				{foreach from=$myDirectoryTree item=path key=id}
					<option value="{$id}"{if ($path == $myFile->getParentPath())} SELECTED{/if}>{$path}</option>
				{/foreach}
				</select>
		</div>
		<input type="submit" value="##MOVE_BUTTON##" class="button" />
	</form>
</div>
{/if}