{if $permission >= _READ_ONLY_}
{if !isset($newsgrand)}
	{include file="newsmessage.tpl"}
{/if}

	{if isset($theArticle)}
		{assign var="idNews" value=$theArticle->getID()}
        {assign var="groupid" value=$theArticle->getGroup()}
		<div class="aNews">
			<div class="time">{$theArticle->getDate()}</div>
			<h1>{if $theArticle->getTitle()==""}##NOTITLE##{else}{$theArticle->getTitle()}{/if}</h1>
			<div class="author">
				##BY##
                {if $theArticle->getGroup()==""}
			{userlink user=$theArticle->getAuthorObject() showpicture=$islogged}
            {elseif !isset($newsgrand)}
            <a href="{kurl app='annuaire' page='groupid' id=$theArticle->getGroup()}"> {$group} </a>
            {else}
            <a href="{kurl app='annuaire' page='groupid' id=$theArticle->getGroup()}"> {$group[$idNews]} </a>
            {/if}
				{*<a href="{kurl app="annuaire" username=$theArticle->getAuthorLogin()}">{$theArticle->getAuthor()}</a>*}
			</div>
		{if ($theArticle->getAuthorId() == $currentUserId) || $grouparray[$groupid]=="admin" || $isadmin}
			<div class="controls">
                
				<form action="{kurl app="news" page="modify" id="$idNews"}" method="get">
					<input type="submit" value="##MODIFY##" />
				</form>
				<form action="{kurl action="post"}" method="post">
					<input type="hidden" name="postType" value="delNews">
					<input type="hidden" name="id" value="{$idNews}">
					<input type="submit" value="##DELETE##" onclick="return confirm('##SURE_TO_DELETE_ARTICLE##');"/>
				</form>
			</div>
		{/if}
			<div class="groupsdestination">{*##NEWS_GROUPS_DESTINATION## {$theArticle->getGroupName()}*}&nbsp;</div>
			<div class="content">{$theArticle->getContentXHTML()}</div>
			{if $permission > _READ_ONLY_}
			<div class="commentsHidden">
				{$theArticle->getNbComments()} ##COMMENT##{if (($theArticle->getNbComments())>1)}s{/if} 
				{if ($theArticle->getNbComments()>0)}
					({if $displayComments == 0}<a href="{kurl page="view" id=$theArticle->getID() displayComments="1"}">##DISPLAY_COMMENTS##</a>{else}<a href="{kurl page="view" id=$theArticle->getID() displayComments="0"}">##HIDE_COMMENTS##</a>{/if})
				{/if}
				<a href="{kurl page="addcomment" id=$idNews}">##ADD_COMMENT##</a>
			</div>
			{/if}
			{if (isset($addComment))}
				<div class="newNewsCommentForm">
					<h3>##ADD_A_COMMENT_TO_ARTICLE## "{if $theArticle->getTitle()==""}##NOTITLE##{else}{$theArticle->getTitle()}{/if}"</h3>
					<form action="{kurl action="post"}" method="post">
						<input type="hidden" name="postType" value="newNewsComment">
						<input type="hidden" name="id_news" value="{$theArticle->getId()}" />
						<input type="hidden" name="id_parent" value="" />
						<label for="title">##COMMENT_TITLE## :</label> <input type="text" name="title" id="title" size="40" value="{if ($theArticle->getID()==$theNewsCurrentComment.newsId)}{$theNewsCurrentComment.title}{/if}"/>
						<label for="description">##COMMENT_DESCRIPTION## :</label><textarea name="content" id="description" rows="5" cols="80">{if ($theArticle->getId()==$theNewsCurrentComment.newsId)}{$theNewsCurrentComment.content}{/if}</textarea>
						<input type="submit" value="##POST_COMMENT_ON_ARTICLE##" class="button" />
					</form>
				</div>
			{elseif $displayComments == 0}
			{else}
			<div class="commentsShown">
				{section name=j loop=$theArticleComments step=1}
				<div class="aComment">
					<div class="author">
						##BY## 
						{userlink user=$theArticleComments[j]->getAuthorObject() showpicture=true}
						{*<a href="{kurl app="annuaire" username=$theArticleComments[j]->getAuthorLogin()}">{$theArticleComments[j]->getAuthor()}</a>*}
					</div>
					{*<div class="controls"><a href="{$theArticleComments[j]->getID()}">Modifier</a> - <a href="#d/c/{$theArticleComments[j]->getID()}">Supprimer</a></div>*}
					<div class="time">{$theArticleComments[j]->getDate()}</div>
					<div class="title">{$theArticleComments[j]->getTitle()}</div>
					<div class="content">{$theArticleComments[j]->getContentXHTML()}</div>
				</div>
				{/section}
			</div>
			{/if}
		</div>
	{else}
			<div class="error">
		{if (isset($notingroup) && $notingroup == TRUE)}
				##NOT_AUTHORIZED_TO_VIEW##
		{else}
				##ARTICLE_NOT_FOUND##
		{/if}
			</div>
	{/if}
{else}
	####
{/if}
