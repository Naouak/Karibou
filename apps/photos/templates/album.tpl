page for albums <br />
<div class="photos_album_place">
emplacement :
{foreach item=path from=$parentpath}
<a href={kurl page=$path.2 id=$path.1}>{$path.0}</a>/
{/foreach}
</div>
<br />
<div class="photos_album_pictures">
{foreach from=$pictures item=picture}
    {$picture.id} <br />
{/foreach}
</div>
