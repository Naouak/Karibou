{if $page == "default"}
	<li><strong>##NETJOBS##</strong></li>
{else}
	<li><a href="{kurl}">##NETJOBS##</a></li>
{/if}

{if $page == "jobedit"}
	{if isset($jobid)}
	<li><strong>##JOBEDIT##</strong></li>
	{else}
	<li><strong>##JOBADD##</strong></li>
	{/if}
{else}
	<li><a href="{kurl page="jobedit"}">##JOBADD##</a></li>
{/if}