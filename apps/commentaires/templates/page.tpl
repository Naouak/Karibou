{literal}
<script type="text/javascript">
	function todelete(id) {
		new Ajax.Request({/literal}'{kurl action="formdelete"}'{literal}, {
			method: 'post',
			parameters: 'id=' + encodeURIComponent(id),
			onSuccess: function success() {}
		});
		return false;
	}
</script>
{/literal}


<div class="commentaires">
    {if $title!=""}
    <h1 class="commentaires-page-title">{$title}</h1>
    {/if}
    {if $content!=""}
    <div class="commentaires-page-content">
        {$content}
    </div>
    {/if}
    <div class="commentaires-page-comments">
        <h2 class="commentaires-page-commentstitle">##Comments##</h2>
        <ul>
	{foreach item=oldcomment from=$existing}
            <li>
                <ul class="commentaires-page-commentconainer">
                    <li class="commentaires-page-commentername">
                        {userlink user=$oldcomment.user showpicture=true}
                    </li>
                    <li class="commentaires-page-commentercomment">
                        {$oldcomment.comment|wordwrap:34:" ":true|nl2br}
                    </li>
					<a href="{kurl page="modify" id=$oldcomment.id parent=$id}"> ##modify##</a>
					<a onclick="todelete({$oldcomment.id});">##delete##</a>
                </ul>

            </li>
            {/foreach}
        </ul>
    </div>

    <div class="commentaires-page-form">
        <form action='{kurl action="postcomment"}' method="POST">
            <input type="hidden" name="id" value={$id} />
            <fieldset>
                <legend class="commentaires-page-formlegend">##Add comment##</legend>
                <textarea name="comment"></textarea>
                <input type="submit" value="##Add comment##" />
            </fieldset>

        </form>
    </div>
</div>
