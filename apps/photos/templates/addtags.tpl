<strong>Add tags : </strong>
<div class="photos-addtags-form">
<form action={kurl action="tags"} method="POST">
	<div class="photos_addtags_input">
		<label for="tag"> {t} Veuillez saisir la liste des tags correspondants à votre {$type} {/t} <input type="text" name="tag" id="tag" /></label>
		<input type="hidden" name="type" value="{$type}" />
		<input type="hidden" name="id" value="{$parent}" />
	</div>
	<div class="photos_addtags_submit">
		<input type="submit" value="ajouter des tags"/>
	</div>
</form>
</div>
<div class="photos-addtags-existingtags">
<strong>Existing tags for the container</strong><br />
{foreach from=$tags item=tag}
	{$tag.name}{if !$smarty.foreach.tags.last}, {/if}
{/foreach}
</div>
<div class="photos-addtags-alltags">
<strong>All existing tags </strong> <br />
{foreach from=$alltags item=tag name=tags}
	{$tag.name}{if !$smarty.foreach.tags.last}, {/if}
{/foreach}
</div>