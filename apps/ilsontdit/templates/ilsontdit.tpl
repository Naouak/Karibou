{foreach item=quote from=$quotes}
<p>
<b>{$quote.author}</b><br /> {$quote.reporter}{$currentuser}{$isadmin}
<p style="padding-left: 0.5em;">
	<em>&#8220;&nbsp;{$quote.text|wordwrap:34:" ":true|nl2br}&nbsp;&#8221;</em> {if $quote.reporter == $currentuser || $isadmin}<a href="{kurl app="ilsontdit" page="modify2" id=$quote.id }">modifier</a>{/if}
</p>
{/foreach}
</p>
