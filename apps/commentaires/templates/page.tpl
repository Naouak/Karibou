{literal}
<script type="text/javascript">
	function todelete(id) {
		/* THIS IS DEFAULT 2 ! */
		/* DEFAULT 2... THIS IS COMMENTS ! */
		var form = document.createElement("form");
		form.setAttribute("method", "POST");
		form.setAttribute("action", {/literal}'{kurl action="formdelete"}'{literal});
		
		var field = document.createElement("input");
		field.setAttribute("type", "hidden");
		field.setAttribute("name", "id");
		field.setAttribute("value", id);

		form.appendChild(field);

		document.body.appendChild(form);

		form.submit();
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
	{if count($existing)!=0}
    <div class="commentaires-page-comments">
        <h2 class="commentaires-page-commentstitle">##Comments##</h2>
        <ul>
	{foreach item=oldcomment from=$existing}
            <li>
                <ul class="commentaires-page-commentcontainer {if !$oldcomment.read}commentaires-page-commentunread{/if}">
                    <li class="commentaires-page-commentername">
                        {userlink user=$oldcomment.user showpicture=true}
                    </li>
                    <li class="commentaires-page-commentercomment">
                        {$oldcomment.comment|wordwrap:34:" ":true|nl2br}
                    </li>
					{if $isadmin || $currentuser == $oldcomment.user->getId()}
					<li>
						<a href="{kurl page='modify' id=$oldcomment.id parent=$id}"> ##modify##</a>
						<a onclick="todelete({$oldcomment.id});">##delete##</a>
					</li>
					{/if}
                </ul>

            </li>
            {/foreach}
        </ul>
    </div>
	{/if}

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
