{foreach item=link from=$links}
<p>
<b>{userlink user=$link.author showpicture=$isLogged}</b> : <em><a href="{$link.link}" target="_blank"> {$link.title} </a></em>
	{if $link.author == $currentuser || $isadmin}<a onclick="$app(this).modify({$link.id}); return false;">modifier</a>{/if}
	{if $link.author == $currentuser || $isadmin}<a onclick="$app(this).deleteContent({$link.id}); return false;">supprimer</a>{/if}
	{votesbox id=$link.idcombox type="miniapp"}
</p>
{/foreach}
