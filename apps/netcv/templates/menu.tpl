{if $page == "home"}
	<li><strong>##NETCV##</strong></li>
{else}
	<li><a href="{kurl}">##NETCV##</a></li>
{/if}

{if $page == "personalinfo"}
	<li><strong>##PERSOINFO##</strong></li>
{else}
	<li><a href="{kurl page='personalinfo'}">##PERSOINFO##</a></li>
{/if}

{if $page == "cvcontentcheck"}
	<li><strong>##ONLINE##</strong></li>
{else}
	<li><a href="{kurl page='cvcontentcheck'}">##ONLINE##</a></li>
{/if}