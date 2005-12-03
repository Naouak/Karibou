{if $page == "home"}
	{if $mode == "edit" && $permission >= _SELF_WRITE_}
		<li><a href="{kurl}">##WIKI##</a></li>
		<li><strong>##ACTION_MODIFY## {$docu}</strong></li>
		<li><a href="{kurl docu=$docu mode='history'}">##HISTORY## {$docu}</a></li>	
	{elseif $mode == "history" && $permission >= _SELF_WRITE_}
		<li><a href="{kurl}">##WIKI##</a></li>
		<li><a href="{kurl docu=$docu mode='edit'}">##ACTION_MODIFY## {$docu}</a></li>
		<li><strong>##HISTORY## {$docu}</strong></li>
	{else}
		<li><strong>##WIKI##</strong></li>	
		{if $permission >= _SELF_WRITE_}
		<li><a href="{kurl docu=$docu mode='edit'}">##ACTION_MODIFY## {$docu}</a></li>
		<li><a href="{kurl docu=$docu mode='history'}">##HISTORY## {$docu}</a></li>
		{/if}
	{/if} 
	<li><a href="{kurl page='help'}">##TITLE_WIKI_SYNTAX##</a></li>
{elseif $page == "help"}
	<li><a href="{kurl}">##WIKI##</a></li>	
	<li><strong>##TITLE_WIKI_SYNTAX##</strong></li>
{/if}
