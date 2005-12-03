{if $page == "home"}
	<li><strong>##CALENDAR##</strong></li>
{else}
	<li><a href="{kurl}">##CALENDAR##</a></li>
{/if}
{if $page == "addEvent"}
	<li><strong>##ADDEVENT##</strong></li>
{else}
	<li><a href="{kurl page='addEvent'}">##ADDEVENT##</a></li>
{/if}
{if $page == "manage"}
	<li><strong>##MANAGE##</strong></li>
{else}
	<li><a href="{kurl page='manage'}">##MANAGE##</a></li>
{/if}
