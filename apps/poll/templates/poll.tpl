<h3 class="handle">##POLL##</h3>
<span id="poll_votes">
{if $canVote}
<p>
<strong>{$question}</strong>
<ul>
{foreach item=answer key=answerID from=$answers}
<li><a href="#" onclick="new Ajax.Updater('poll_votes', '{kurl app="poll" page="miniVote"}', {ldelim}method: 'post', parameters: 'voteAnswer={$answerID}'{rdelim});">{$answer}</a></li>
{/foreach}
</ul>
</p>
{else}
{include file="votes.tpl"}
{/if}
</span>