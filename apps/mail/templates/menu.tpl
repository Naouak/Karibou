{if $page == "home"}
	<li><strong>##EMAIL##</strong></li>
{else}
	<li><a href="{kurl app='mail'}">##EMAIL##</a></li>
{/if}

{if $page == "compose"}
	<li><strong>##COMPOSELINK##</strong></li>
{else}
	<li><a href="{kurl page='compose'}">##COMPOSELINK##</a></li>
{/if}

{if ($page == "view") && (isset($article_id))}
	{if $viewcomments == true}
		<li><a href="{kurl page='view' id=$article_id displayComments=0}">##HIDE_COMMENTS##</a></li>
	{else}
		<li><a href="{kurl page='view' id=$article_id displayComments=1}">##DISPLAY_COMMENTS##</a></li>
	{/if}
{/if}