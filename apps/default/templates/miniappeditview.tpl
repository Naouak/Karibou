{if $config}
<a href="" title="edit"
	onclick="new Ajax.Updater('{$id}_content', '{kurl page="miniappeditconfig" miniapp=$id}', {ldelim}asynchronous:true, evalScripts:true{rdelim}); return false;" >
	<span class="edit"><span class="text">edit</span></span>
</a>
{/if}
<a href="" title="update"
        onclick="new Ajax.Updater('{$id}_content', '{kurl page="miniappeditview" miniapp=$id}', {ldelim}asynchronous:true, evalScripts:true{rdelim}); return false;" >
        <span class="update"><span class="text">update</span></span>
</a>
<a href="" title="shade"
        onclick="shadeApp('{$id}_content'); return false;" >
        <span class="shade"><span class="text">shade</span></span>
</a>
<a href="" title="del"
	onclick="Effect.Fade('{$id}'); new Ajax.Request(delete_url, {ldelim} asynchronous: true , method: 'post' , postBody: 'delete_id={$id}' {rdelim} ); return false;" >
	<span class="del"><span class="text">del</span></span>
</a>
{hook name=$id}
