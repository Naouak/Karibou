<h1>##FILESHARE_TITLE##</h1>
<div class="fileshare">
{if $allowed}
	<div class="helper">##CREATEDIRECTORY_DESCRIPTION##</div>
	<form action="{kurl action="savedirectory"}" method="post">
		##CREATINGIN## : <strong>/{$directoryname}</strong>
		<br />
		{if (isset($directorynamebase64))}<input type="hidden" name="directoryname" value="{$directorynamebase64}" />{/if}
		<input type="text" name="newdirectoryname" class="directory" />
		<br />
		<label for="description">##DESCRIPTION##</label> : <textarea name="description" cols="40" rows="3"></textarea>
		<div class="owner">
			<label for="owner">##OWNER## :</label>
			<select name="owner" id="owner">
				<option value="">##ME##</option>
				<option value="disabled" disabled="disabled">##GROUPS##</option>
				{include file="optiongrouptree.tpl"}
			</select>
		</div>
		<input type="submit" value="##CREATEDIRECTORY##" class="button" />
	</form>
{else}
	<div class="hint hint_notallowed">##NOACCESS##</div>
{/if}
</div>