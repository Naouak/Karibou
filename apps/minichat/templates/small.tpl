<div class="minichat">
{if !$inversepostorder}
	<div id="minichat_live"  style="display: block; height: {$maxHeight}px; max-height: {$maxHeight}px; overflow: hidden;">
		{include file="content.tpl"}
	</div>
{/if}
    {if $permission > _READ_ONLY_}
    <form autocomplete="off" action="{kurl action="post"}" method="post" id="minichat_live_form">
        <input type="text" name="post" id="message" class="minichatMessage" />
        <input type="submit" value="##MINICHAT_SEND##" class="button" />
    </form>
    {/if}
{if $inversepostorder}
	<div id="minichat_live"  style="display: block; height: {$maxHeight}px; max-height: {$maxHeight}px; overflow: hidden;">
		{include file="content.tpl"}
	</div>
{/if}
    <div class="chathistorylink"><a href="{kurl app="minichat" page="" day="$time"}">##VIEWCHATHISTORY##...</a></div>
</div>
