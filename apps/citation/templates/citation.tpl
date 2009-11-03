<p>
{t}Quote posted by{/t} : {userlink user=$citationauthor.object showpicture=$islogged}
<br />
{$citationnow|wordwrap:34:" ":true}
<br />
		{if $author_id == $currentuser || $isadmin}<a onclick="$app(this).modify({$citation_id}); return false;">modifier</a>{/if}
		{if $author_id == $currentuser || $isadmin}<a onclick="$app(this).deleteContent({$citation_id}); return false;">supprimer</a>{/if}
</p>
<p>
	{commentbox id=$idcombox} <br />
	{votesbox id=$idcombox type="miniapp"}
</p>
