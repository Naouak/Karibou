		{section name=i loop=$theNews step=1}
			{assign var="theArticle" value=$theNews[i]}
			{assign var="idNews" value=$theNews[i]->getID()}
			{*assign var="newsLink" value="kurl app="news" page="view" id=$theArticle->getId()"*}
		<div class="aSmallNews {cycle values="one,two"}">
			<span class="title" title="{$theArticle->getTitle()}"><a href="{kurl app="news" page="view" id=$theArticle->getId()}">{if $theArticle->getTitle()==""}##NOTITLE##{else}{$theArticle->getTitle()|truncate:50:"...":false}{/if}</a></span>
			<span class="author">##BY##
            {if $theArticle->getGroup()==""}
			{userlink user=$theArticle->getAuthorObject() showpicture=$islogged}
            {else}
            <a href="{kurl app='annuaire' page='groupid' id=$theArticle->getGroup()}"> {$groups[i]} </a>
            {/if}
			{* <a href="{kurl app="annuaire" username=$theArticle->getAuthorLogin()}">{$theArticle->getAuthor()}</a>*}
			</span>
			<div class="time">{$theArticle->getDate()}</div>
			<br />
			{$theArticle->getContentXHTML()|strip_tags|truncate:300:"...":false}
			{if ($theArticle->getContentXHTML()|strip_tags|@count_characters:true)>=300}
				<a href="{kurl app="news" page="view" id=$theArticle->getId()}">##DISPLAY_WHOLE_ARTICLE##</a>
			{/if}<br />
			<em><a href="{kurl app="news" page="view" id=$theArticle->getID() displayComments="1"}">{t count=$theArticle->getNbComments() 1=$theArticle->getNbComments() plural="%1 Comments"}%1 Comment{/t}</a></em> : <a href="{kurl page="addcomment" id=$idNews}">##ADD_COMMENT##</a>
		</div>
		{/section}
		
	{if $permission > _READ_ONLY_}
	<div class="addlink"><a href="{kurl page="add"}">##ADD_ARTICLE##</a></div>
	{/if}
	<div class="archivelink"><a href="{kurl page=""}">##NEWS_VIEW_ARCHIVES##...</a></div>
