{if $page == "home"}
	<li><strong>##DIRECTORY##</strong></li>
{else}
	<li><a href="{kurl}">##DIRECTORY##</a></li>
{/if}

{if $page == "search"}
	<li><strong>##USER_SEARCH##</strong></li>
{else}
	<li><a href="{kurl page="search"}">##USER_SEARCH##</a></li>
{/if}

{if $page == "groups"}
	<li><strong>##USER_GROUPS##</strong></li>
{else}
	<li><a href="{kurl page="groups"}">##USER_GROUPS##</a></li>
{/if}

{if $page == "profile"}
	<li><strong>##USER_LINK##</strong></li>
{else}
	<li><a href="{kurl username=$username}">##USER_LINK##</a></li>
{/if}