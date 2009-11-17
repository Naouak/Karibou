<h1> {$bug.title} </h1>
<br />
<br />
{if $bug.doublon_id !== null}
	<p> <a href="{kurl page="view id=$bug.doublon_id}">{t}Doublon n°{/t} {$bug.doublon_id}.</a> </p>
	<div class="modify">	{if $bug.reporter_id == $currentuser || $isadmin || $dev}
			<a href="{kurl page="modify" id=$bug.id}"> {t}Modify{/t}</a><br />
		{/if} </div>
		
	<p> {t}Post by{/t} : {userlink user=$bug_author.object showpicture=true}</p>
	<p> {t}State{/t} : {t}{$bug.state}{/t} </p>
	<p> {t}Importance{/t} : {t}{$bug.type}{/t} </p>
	<p> {t}Browser{/t} : {$bug.browser} </p>
	<p> {t}Description{/t} : {$bug.bug} </p>
	<p> {t}Module{/t} : {$module.name} </p>

{else}
	<p>{votesbox id=$idcombox type="bigapp"} </p>
	<div class="modify">{if $bug.reporter_id == $currentuser || $isadmin || $dev}
		<a href="{kurl page="modify" id=$bug.id}"> {t}Modifier{/t} </a>
	{/if} </div>
	<p> {t}Post by{/t} : {userlink user=$bug_author.object showpicture=true}</p>
	<p> {t}State{/t} : {t}{$bug.state}{/t} </p>
	<p> {t}Importance{/t} : {t}{$bug.type}{/t} </p>
	<p> {t}Browser{/t} : {$bug.browser} </p>
	<p> {t}Description{/t} : {$bug.bug} </p>
{/if}