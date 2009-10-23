the Title <br />
{$title} <br />
<br />
the content <br />
{$content}<br />
<br />
old comments
<br />
{foreach item=oldcomment from=$existing}
    {$oldcomment.comment}
    <br />
{/foreach}
<br />
add comments 
<form action={kurl action="postcomment"} method="POST">
    <input type="text" name="comment" />
    <input type="hidden" name="id" value={$id} />
    <br />
    <input type="submit" value="ajouter un commentaire" />
</form>

