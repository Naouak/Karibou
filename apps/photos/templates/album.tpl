{literal}
<script type="text/css">
	.photos_album_place a:visited {
		font-color : red;
	}
	.tets {
		font-color: red;
		background-color : red;
	}
</script>
{/literal}

<strong>page for albums </strong><br />
<div class="photos_album_place">
	emplacement :
	{foreach item=path from=$parentpath}
		<a href={kurl page=$path.2 id=$path.1}>{$path.0}</a>/
	{/foreach} 
	| <a href={kurl page=""}>{t}Page d'accueil{/t}</a>
</div>
<br />
<div class="photos_album_pictures">
	<strong> list of the pictures :  </strong><br /><br />
	{foreach from=$pictures item=picture}
		{$picture.id} <br />
	{/foreach}
</div>
<div class="photos_album_tags">
	<strong>List of tags from this album : </strong>
	{foreach from=$tags item=tag}
		{$tag.name} <br />
	{/foreach}
	<br />
	<span class="tets"><a href={kurl page="addtags" parent=$id type=$type}> {t}ajouter des tags{/t}</a></span><br />
</page>