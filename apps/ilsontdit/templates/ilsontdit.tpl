{foreach item=quote from=$quotes}
	<div class="default2-miniapp-subheader">
		<strong>{$quote.author}</strong>
		<div class="default2-miniapp-subheader-vote">{votesbox id=$quote.idcombox type="miniapp"}</div>
	</div>

	<p style="text-align: justify;">
		<em>&#8220;&nbsp;{$quote.text|wordwrap:34:" ":true|nl2br}&nbsp;&#8221;</em>
	</p>

	{if $isadmin || $quote.reporter == $currentuser}
	<p style="text-align: center;">
		<a onclick="$app(this).modify({$quote.id}); return false;">modifier</a>
		<a onclick="$app(this).deleteContent({$quote.id}); return false;">supprimer</a>
	</p>
	{/if}
{/foreach}
