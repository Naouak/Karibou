<form action="{kurl action='formmodify'}" method="POST">
	<input type="hidden" name="id" value="{$id}" />
	<input type="hidden" name="parent" value="{$parent}" />
	<fieldset>
		<legend> ##MODIFY##</legend>
	<textarea class="commentaires-modify-textarea" name="comment">{$tomodify.comment}</textarea>
	</fieldset>
	<input type="submit" value="##MODIFY##">
	<input type="button" value="##CANCEL##" onclick="javascript:history.go(-1);">
</form>
