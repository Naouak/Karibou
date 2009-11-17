<div class="photos-fromtags-form">
	<form action="{kurl action='research'}" method="POST">
		<label for="tags">{t}Entrez les tags de recherche : {/t}<input type="text" name="tags" id="tags" /></label>
		<input type="submit"/>
	</form>
</div>
<div class="photos-fromtags-alltags">
<strong>{t}Liste des tags existants{/t}</strong> : <br />
	{foreach from="$alltags" item="tag"}
		{$tag.name},
	{/foreach}
</div>