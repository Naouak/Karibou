{if $page == "home"}
	<li><strong>##NETCV##</strong></li>
	<li><a href="{kurl page='personalinfo'}">##PERSOINFO##</a></li>
	<li><a href="{kurl page='cvcontentcheck'}">##ONLINE##</a></li>
{/if}
{if $page == "personalinfo"}
	<li><a href="{kurl}">##TITLE_WIKI_SYNTAX##</a></li>
	<li><strong>##PERSOINFO##</strong></li>	
	<li><a href="{kurl page='cvcontentcheck'}">##ONLINE##</a></li>
{/if}	
{if $page == "cvcontentcheck"}
	<li><a href="{kurl}">##TITLE_WIKI_SYNTAX##</a></li>
	<li><a href="{kurl page='personalinfo'}">##PERSOINFO##</a></li>
	<li><strong>##ONLINE##</strong></li>
{/if}
