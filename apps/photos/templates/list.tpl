<h1>##Photo##</h1>
<div class="photos-content">
<div class="photos-index-tags photos-main-container">
    <h2>list of tags </h2>
    <ul>
        {foreach item=tag from=$tags}
            <li>{$tag.name}</li>
        {/foreach}
    </ul>
</div>

<div class="photos-index-albums photos-main-container">
    <h2>list of all albums </h2>
    <ul class="photos-list-thumb">
        {foreach item=album from=$albums}
            <li>
                <a href={kurl page="album" id=$album.id}>
                    <span class="photos-list-caption">{$album.name}</span>
                </a>
            </li>
        {/foreach}
    </ul>
</div>



<div class="photos_accueil_slash">
    <a href="{kurl page="carton" id=$idslash}">{t}Navigation dans les cartons et les albums{/t}</a>
</div>
</div>
