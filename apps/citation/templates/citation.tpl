<p>
{t}Quote posted by{/t} : {userlink user=$citationauthor.object showpicture=$islogged}
<br />
{$citationnow|wordwrap:34:" ":true}
<br />
		{if $author_id == $currentuser || $isadmin}<a onclick="$app(this).modify({$citation_id}); return false;">modifier</a>{/if}
		{if $author_id == $currentuser || $isadmin}<a onclick="$app(this).deleteContent({$citation_id}); return false;">supprimer</a>{/if}
</p>
<p>
	<a href="{kurl app="commentaires"  id=$idcombox}" > test </a>
	{*kurl app="commentaires"  id=$idcombox*}
	{commentbox id=$idcombox} <br />
	{votesbox id=$idcombox type="miniapp"}
</p>