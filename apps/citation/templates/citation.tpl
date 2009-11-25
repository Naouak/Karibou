<div class="default2-miniapp-subheader">
	<em>{t}Quote posted by{/t} : {userlink user=$citationauthor.object showpicture=$islogged}</em>
	<div class="default2-miniapp-subheader-vote">{votesbox id=$idcombox type="miniapp"}</div>
</div>
<div class="default2-citation-blockquote">
    <q>{$citationnow|wordwrap:34:" ":true|nl2br}</q>
</div>

{if $isadmin}
<p style="text-align: center;">
	<a onclick="$app(this).modify('{$citation_id}'); return false;">modifier</a>
	<a onclick="$app(this).deleteContent('{$citation_id}'); return false;">supprimer</a>
</p>
{/if}
