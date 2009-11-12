<h1>Bugs</h1>
<strong><a href="{kurl page="add"}">Ajouter un bug</a></strong>

<br /><br />

<p> <strong>Bugs nécessitant plus d'informations : </strong></p>
	<br />
	{foreach item=bug from=$bugs}
		{if $bug.state == "Besoin d'informations"} <a href="{kurl page="view" id=$bug.id }">{$bug.title}</a> <br />{/if}
	{/foreach}

<br /> <br />

<p><strong> Bugs en attente : </strong></p>
	<br />
	{foreach item=bug from=$bugs}
	
		{if $bug.state == "En attente"}<a href="{kurl page="view" id=$bug.id }">{$bug.title}</a><br /> {/if}
	{/foreach}

<br /> <br />

<p> <strong>Bugs confirmés : </strong></p>
	<br />
	{foreach item=bug from=$bugs}
		{if $bug.state == "Pris en compte"} <a href="{kurl page="view" id=$bug.id }">{$bug.title}</a> <br />{/if}
	{/foreach}</p>

<br /> <br />

<p><strong> Bugs corrigés :</strong> </p>
	<br />
	{foreach item=bug from=$bugs}
		{if $bug.state == "Resolu"} <a href="{kurl page="view" id=$bug.id }">{$bug.title}</a><br />{/if}
	{/foreach}
	








