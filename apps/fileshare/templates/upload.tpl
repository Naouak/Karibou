<h1>##FILESHARE_TITLE##</h1>
<div class="fileshare">
{if $allowed}
	<div class="helper">{if isset($myFile)}##FILEUPLOAD_ADDVERSION_DESCRIPTION##{else}##FILEUPLOAD_DESCRIPTION##{/if}</div>
	<form action="{kurl action="savefile"}" method="post" enctype="multipart/form-data">
	{if isset($myFile)}
		##ADDING_A_NEW_VERSION##  <strong>{$myFile->getSysInfos("name")}</strong>
		<input type="hidden" name="fileid" value="{$myFile->getElementId()}" />
	{else}
		##UPLOADINGTO## : <strong>/{$directoryname}</strong>
		<br />
		{if (isset($directorynamebase64))}<input type="hidden" name="directoryname" value="{$directorynamebase64}" />{/if}
	{/if}
		<br />
		<input type="file" name="file" class="file" />
		<br />
		<label for="description">##DESCRIPTION##</label> : <textarea name="description" cols="40" rows="3"></textarea>
		<br />
	{if !isset($myFile)}
		<div class="owner">
			<label for="owner">##OWNER## :</label>
			<select name="owner" id="owner">
				<option value="">##ME##</option>
				<option value="disabled" disabled="disabled">##GROUPS##</option>
				{include file="optiongrouptree.tpl"}
			</select>
		</div>
	{/if}
		<input type="submit" value="##UPLOAD_FILE##" class="button" />
	</form>
{else}
	<div class="hint hint_notallowed">##NOACCESS##</div>
{/if}
</div>