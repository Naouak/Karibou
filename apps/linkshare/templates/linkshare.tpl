<ul>
    {foreach item=link from=$links}
	<li class="default2-linkshare-link">
	    <div style="float: right;">{votesbox id=$link.idcombox type="miniapp"}</div>

	    {userlink user=$link.author showpicture=$isLogged} : <a href="{$link.link}" target="_blank">{$link.title}</a>
	    <div class="default2-linkshare-modify">
		<span class="default2-linkshare-linkis">Le lien est: {$link.link}</span>
		{if $link.author == $currentuser || $isadmin}<a onclick="$app(this).modify({$link.id}); return false;">modifier</a>{/if}
		{if $link.author == $currentuser || $isadmin}<a onclick="$app(this).deleteContent({$link.id}); return false;">supprimer</a>{/if}
	    </div>

	</li>
    {/foreach}
</ul>
