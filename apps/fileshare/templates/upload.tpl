<h1>##FILESHARE_TITLE##</h1>
<div class="fileshare">
	<div class="helper">##FILEUPLOAD_DESCRIPTION##</div>
	<form action="{kurl action="savefile"}" method="POST" enctype="multipart/form-data">
		##UPLOADINGTO## : <strong>/{$directoryname}</strong>
		<br />
		{if (isset($directorynamebase64))}<input type="hidden" name="directoryname" value="{$directorynamebase64}" />{/if}
		<input type="file" name="file" class="file" />
		<input type="submit" value="##UPLOAD_FILE##" class="button">
	</form>
</div>
