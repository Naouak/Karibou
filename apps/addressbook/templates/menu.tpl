{if $page == "home"}
	<li><strong>##ADDRESSBOOK##</strong></li>
{else}
	<li><a href="{kurl app='addressbook'}">##ADDRESSBOOK##</a></li>
{/if}

{if $page == "add"}
	<li><strong>##CONTACT_ADD##</strong></li>
{else}
	<li><a href="{kurl page='add'}">##CONTACT_ADD##</a></li>
{/if}

{if $page == "profile" && isset($profile_id)}
	<li><a href="{kurl page='' profile_id=$profile_id act=edit}">##EDIT## {$profile->get}</a></li>
{/if}