<h1>##Photo##: ##Folders##</h1>
<div class="photos-content">
<div class="photos-place">
    <ul class="photos-place-list">
	{foreach item=path from=$parentpath}
        <li><a href={kurl page="carton" id=$path.1}>{$path.0}</a></li>
	{/foreach}
    </ul>
	| 
    <span class="photos-place-returnlink"><a href={kurl page=""}>{t}Page d'accueil{/t}</a></span>
</div>
<div class="photos-folder-containers photos-main-container">
    <h2>folders list</h2>
    <ul class="photos-list-thumb">
        {foreach  item=child from=$children}
        <li>
            <a href={kurl page=$child.type id=$child.id}>
                <span class="photos-list-caption">{$child.name}</span>
            </a>
        </li>
        {/foreach}
    </ul>

</div>

<div class="photos-folder-taglist photos-main-container">
    <h2>List of tags</h2>
    <ul>
        {foreach from=$tags item=tag}
            <li>{$tag.name}</li>
        {/foreach}
    </ul>
</div>

<div class="photos-folder-add">
    <ul class="photos-folder-add-list">
        <li><a href={kurl page="addtags" parent=$parent type=$type}>{t}add tags{/t}</a></li>
        <li><a href={kurl page="addcontainer" parent=$parent}>{t}add a container{/t}</a></li>
    </ul>
</div>
</div>