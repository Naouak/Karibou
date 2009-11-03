list of all albums <br />
{foreach item=album from=$albums}
    {$album.name} <br />
{/foreach}

list of tags <br />

Folder <br />
<a href="{kurl page="folder" id=$idslash}"> Racine </a><br />
