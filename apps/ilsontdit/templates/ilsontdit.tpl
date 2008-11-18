<h3 class="handle">{t}That's what they said{/t}</h3>
{foreach item=quote from=$quotes}
<p>
<b>{$quote.author}</b><br />
<p style="padding-left: 0.5em;">
	<em>&#8220;&nbsp;{$quote.text|wordwrap:34:" ":true|nl2br}&nbsp;&#8221;</em>
</p>
{/foreach}
</p>
