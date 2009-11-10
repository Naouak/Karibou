folders list
<br />
<div class="photos_folder_place">
emplacement : 
{foreach item=path from=$parentpath}
<a href={kurl page="carton" id=$path.1}>{$path.0}</a>/
{/foreach}
</div>
<br />
<div class="photos_folder_containers">
{foreach  item=child from=$children}
        <a href={kurl page=$child.type id=$child.id}> {$child.name} </a>
    <br />
{/foreach}
<br />
<div>

<div class="photos_folder_addcontainer">
<br />
<a href={kurl page="addcontainer" parent=$parent}> {t}add a container{/t}</a>
</div>
