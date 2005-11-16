<h1>##FILESHARE_TITLE##</h1>
<div class="fileshare">
	<div class="helper">##CREATEDIRECTORY_DESCRIPTION##</div>
	<form action="{kurl action="savedirectory"}" method="POST">
		##CREATINGIN## : <strong>/{$directoryname}</strong>
		<br />
		{if (isset($directorynamebase64))}<input type="hidden" name="directoryname" value="{$directorynamebase64}" />{/if}
		<input type="text" name="newdirectoryname" class="directory" />
		<input type="submit" value="##CREATEDIRECTORY##" class="button">
	</form>
</div>