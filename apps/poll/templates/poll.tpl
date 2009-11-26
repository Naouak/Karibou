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
<div>
	<a href="{kurl app='commentaires' id=$commentId}" class="lightbox lightbox-iframe">{commentbox id=$commentId} ##Comments##</a>
</div>
