{if $page == "default"}
	<li><strong>##DATAVIEW##</strong></li>
{else}
	<li><a href="{kurl}">##DATAVIEW##</a></li>
{/if}

{if $page == "list"}
	<li>##DV_LIST##</li>
{else}
	{if $page == "details" || $page == "search" }
	<li><a href="{kurl page='list' source=$source}">##DV_LIST##</a></li>
	{/if}
{/if}

{if $page == "search"}
	<li><strong>##DV_SEARCH##</strong></li>
{else}
	<li><a href="{kurl page='search' source=$source}">##DV_SEARCH##</a></li>
{/if}

{if $page == "details"}
	<li><strong>##DV_DETAILS##</strong></li>
{/if}