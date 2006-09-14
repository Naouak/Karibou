{if $page == "home"}
	<li><strong>##NEWS##</strong></li>
{else}
	<li><a href="{kurl app='news'}">##NEWS##</a></li>
{/if}

{if $page == "add"}
	<li><strong>##ADD_ARTICLE##</strong></li>
{elseif ($writeperm)}
	<li><a href="{kurl page='add'}">##ADD_ARTICLE##</a></li>
{/if}

{if ($page == "view") && (isset($article_id))}
	{if $viewcomments == true}
		<li><a href="{kurl page='view' id=$article_id displayComments=0}">##HIDE_COMMENTS##</a></li>
	{else}
		<li><a href="{kurl page='view' id=$article_id displayComments=1}">##DISPLAY_COMMENTS##</a></li>
	{/if}
{/if}