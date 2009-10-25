{foreach item=quote from=$quotes}
<p>
<b>{$quote.author}</b><br />
<p style="padding-left: 0.5em;">
	<em>&#8220;&nbsp;{$quote.text|wordwrap:34:" ":true|nl2br}&nbsp;&#8221;</em>
		 {if $quote.reporter == $currentuser || $isadmin}<a onclick="$app(this).modify({$quote.id}); return false;">modifier</a>{/if}
		 {if $quote.reporter == $currentuser || $isadmin}<a onclick="$app(this).deleteContent({$quote.id}); return false;">supprimer</a>{/if}
</p>
{/foreach}
</p>
