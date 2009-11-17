<strong>page for albums </strong><br />
<div class="photos-album-place">
	<strong>emplacement : </strong>
	{foreach item=path from=$parentpath}
		<a href={kurl page=$path.2 id=$path.1}>{$path.0}</a>/
	{/foreach} 
	| <a href={kurl page=""}>{t}Page d'accueil{/t}</a>
</div>
<br />
<div class="photos-album-pictures">
	<strong> list of the pictures :  </strong><br /><br />
	{foreach from=$pictures item=picture}
		<a href="">{$picture.id}</a><a href="{kurl page='photo' id=$picture.id}">{t}gestion de la photo{/t}</a> <br />
	{/foreach}
</div>
<div class="photos-album-tags">
	<strong>List of tags from this album : </strong>
	{foreach from=$tags item=tag}
		{$tag.name} <br />
	{/foreach}
	<br />
	<a href={kurl page="addtags" parent=$id type=$type}>{t}ajouter des tags{/t}</a><br />
</div>