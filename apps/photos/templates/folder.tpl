<strong>Place : </strong>
<br />
<div class="photos_folder_place">
	emplacement : 
	{foreach item=path from=$parentpath}
		<a href={kurl page="carton" id=$path.1}>{$path.0}</a>/
	{/foreach}
	| <a href={kurl page=""}>{t}Page d'accueil{/t}</a>
</div>
<br />
<div class="photos_folder_containers">
	<strong>folders list</strong><br />
	{foreach  item=child from=$children}
		<a href={kurl page=$child.type id=$child.id}> {$child.name} </a>
		<br />
	{/foreach}
	<br />
<div>

<div class="photos_folder_add">
	<b>List of tags</b><br />
	{foreach from=$tags item=tag}
		{$tag.name}<br />
	{/foreach}
	<br />
	
	<a href={kurl page="addtags" parent=$parent type=$type}>{t} add tags{/t} </a>
	<br  />
	<a href={kurl page="addcontainer" parent=$parent}> {t}add a container{/t}</a>
</div>
