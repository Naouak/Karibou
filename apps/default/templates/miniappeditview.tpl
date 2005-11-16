{if $config}
<a href="" onclick="new Ajax.Updater('{$id}_content', '{kurl page="miniappeditconfig" miniapp=$id}', {ldelim}asynchronous:true, evalScripts:true{rdelim}); return false;" >
	<span class="edit">edit</span>
</a>
{/if}
{hook name=$id}