<h3 class="handle">##THEYSAIDIT##</h3>
{foreach item=quote from=$quotes}
<p>
<b>{$quote.author}</b> : {$quote.text|wordwrap:34:" ":true}
<br />
{/foreach}
</p>
