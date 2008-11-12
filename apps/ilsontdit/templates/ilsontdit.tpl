<h3 class="handle">##THEYSAIDIT##</h3>
{foreach item=quote from=$quotes}
<p>
<b>{$quote.author}</b><br />
<p style="padding-left: 1em;">
	<em>&#8220; {$quote.text|wordwrap:34:" ":true|nl2br} &#8221;</em>
</p>
{/foreach}
</p>
