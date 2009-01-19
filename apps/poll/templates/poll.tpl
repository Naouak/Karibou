<span id="poll_votes">
{if $canVote}
<span id="canVote" />
<p>
<strong>{$question}</strong>
<ul>
{foreach item=answer key=answerID from=$answers}
<li><a href="#" onclick="$app(this).vote({$answerID}); return false;">{$answer}</a></li>
{/foreach}
</ul>
</p>
{else}
{include file="votes.tpl"}
{/if}
</span>
