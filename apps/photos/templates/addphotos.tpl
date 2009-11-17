<div class="photos-addphotos-form">
<form action="{kurl action='photos'}" method="POST">
	<label for="file"> {t}Charger votre image{/t}<input type="file" name="file" id="file" /><br /></file>
	<label for="name">{t}Nom de votre photos{/t}<input type="text" name="name" id="name" /><br /></label>
	<label for="tags"> {t}Les tags{/t}<input type="text" name="tags" id="tags" /><br /></label>
	<input type="hidden" name="parent" id="parent" value="{$parent}" />
	<input type="submit"/>
</form>
</div>