<div style="background-color: #eeeeee;">
	<em>{t}Quote posted by{/t} : {userlink user=$citationauthor.object showpicture=$islogged}</em>
	<div style="float: right;">{votesbox id=$idcombox type="miniapp"}</div>
</div>

<p style="text-align: justify;">
	<strong>&#8220;</strong>&nbsp;{$citationnow|wordwrap:34:" ":true|nl2br}&nbsp;<strong>&#8221;</strong>
</p>

{if $isadmin}
<p style="text-align: center;">
	<a onclick="$app(this).modify('{$citation_id}'); return false;">modifier</a>
	<a onclick="$app(this).deleteContent('{$citation_id}'); return false;">supprimer</a>
</p>
{/if}
