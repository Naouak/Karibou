{if $title!=""}
	<div class="comments_title_title">
		The Title :  
	</div>
	<div class="comments_title_content">
		{$title} 
	</div>
	<br />
{/if}
<br />
{if $content!=""}
	<div class="comments_content_title">
		The content : 
	</div>
	<div class="comments_content_content">
		{$content}
	</div>
	<br />
{/if}
<div class="comments_old_title">
	Old comments : 
</div>
<div class="comments_old_content">
	{foreach item=oldcomment from=$existing}
		{$oldcomment.comment} <br />
	{/foreach}
</div>
<br />
<div class="comments_new_title">
	Add comments : 
</div>
<div class="comments_new_content">
	<form action="{kurl action="postcomment"}" method="POST">
		<input type="text" name="comment" />
		<input type="hidden" name="id" value={$id} />
		<br />
		<input type="submit" value="Ajouter un commentaire" />
	</form>
</div>
