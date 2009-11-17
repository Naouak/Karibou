<h1>{t}BUGS{/t}</h1>

<p>{t}TEN LAST BUGS :{/t}</p>
{foreach item=bug from=$bugsmini}
	<p><a href="{kurl page="view" id=$bug.id }">{$bug.summary}</a> </p>
{/foreach}