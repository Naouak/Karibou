<h1>Bugs</h1>

<p> Les dix derniers bugs reportés : </p>
{foreach item=bug from=$bugsmini}
	<a href="{kurl page="view" id=$bug.id }">{$bug.title}</a> <br />
{/foreach}