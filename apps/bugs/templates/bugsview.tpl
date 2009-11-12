<h1> {$bug.title} </h1>
<br />
<br />
{if $bug.doublon == 1}
	<p> Ce bug est le doublon du bug n° {$bug.doublon_id}. </p> <br /><br />
		<br />{if $bug.user_id == $currentuser || $isadmin || $current_module.developper_id == $currentuser}
			<a href="{kurl page="modify" id=$bug.Id}"> Modifier </a><br />
		{/if} <br />
		<p> Posté par : {userlink user=$bug_author.object showpicture=true}</p>
		<p> État : {$bug.bug_state} </p>
		<p>Gravité : {$bug.bug_type} </p>
		<p>Navigateur : {$bug.Browser} </p>
		<p> Description : {$bug.bug} </p>

{else}
	<div style="float: right;">{votesbox id=$idcombox type="bigapp"}</div>
	<br /> {if $bug.user_id == $currentuser || $isadmin || $current_module.developper_id == $currentuser}
		<a href="{kurl page="modify" id=$bug.Id}"> Modifier </a>
	{/if} <br /> <br />
	<p> Posté par : {userlink user=$bug_author.object showpicture=true}</p>
	<p> État : {$bug.bug_state} </p>
	<p>Gravité : {$bug.bug_type} </p>
	<p>Navigateur : {$bug.Browser} </p>
	<p> Description : {$bug.bug} </p>
{/if}