
<div class="photos_accueil_albums">
<b>list of all albums </b><br />
{foreach item=album from=$albums}
    <a href={kurl page="album" id=$album.id}>{$album.name}  </a><br />
{/foreach}
</div>
<br />
<div class="photos_accueil_tags">
<b>list of tags </b><br />
{foreach item=tag from=$tags}
	{$tag.name}<br />
{/foreach}
</div>
<br />
<div class="photos_accueil_slash">
<a href="{kurl page="carton" id=$idslash}">{t}Navigation dans les cartons et les albums{/t}</a><br />
</div>
