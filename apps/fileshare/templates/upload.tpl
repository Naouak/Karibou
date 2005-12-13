<h1>##FILESHARE_TITLE##</h1>
<div class="fileshare">
	<div class="helper">##FILEUPLOAD_DESCRIPTION##</div>
	<form action="{kurl action="savefile"}" method="post" enctype="multipart/form-data">
		##UPLOADINGTO## : <strong>/{$directoryname}</strong>
		<br />
		{if (isset($directorynamebase64))}<input type="hidden" name="directoryname" value="{$directorynamebase64}" />{/if}
		<input type="file" name="file" class="file" />
		<br />
		<label for="description">##DESCRIPTION##</label> : <textarea name="description" cols="40" rows="3"></textarea>
		<br />
		<div class="owner">
			<label for="owner">##OWNER## :</label>
			<select name="owner" id="owner">
				<option value="">##ME##</option>
				<option value="disabled" disabled="disabled">##GROUPS##</option>
				{include file="optiongrouptree.tpl"}
			</select>
		</div>
		<input type="submit" value="##UPLOAD_FILE##" class="button" />
	</form>
</div>