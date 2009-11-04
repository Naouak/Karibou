folders list

{foreach from=$childrem item=child}
    {$child.name}
{/foreach}

<a href={kurl page="addcontainer" parent=$parent}> {t}add a container{/t}</a>
