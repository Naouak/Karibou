<div class="news">
	<h1>##NEWS##</h1>
	<h2>{if isset($theNewsToModify)}##MODIFY_ARTICLE##{else}##ADD_ARTICLE##{/if}</h2>
	<div class="newNewsForm">
		{*<a href="{kurl app="wiki" page="help"}" >##TITLE_WIKI_SYNTAX##</a>*}
		<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	{include file="newsmessage.tpl"}
	{if (!isset($notauthorized))}
				<form action="{kurl action="post"}" method="post">
					<input type="hidden" name="postType" value="newNews">
					{if isset($theNewsToModify)}
						<input type="hidden" name="id" value="{if isset($theNewsToModify)}{$theNewsToModify->getId()}{/if}">
					{/if}
					<div class="field title">
						<label for="title">##NEWS_TITLE## :</label> <input type="text" id="title" name="title" value="{if isset($theNewsToModify)}{$theNewsToModify->getTitle()|escape:"html"}{/if}"/>
					</div>
					<div class="field description">
						<label for="description">##NEWS_DESCRIPTION## : </label><textarea name="content" id="description" rows="10" cols="60" />{if isset($theNewsToModify)}{$theNewsToModify->getContent()|escape:"html"}{/if}</textarea>
					</div>
					<input type="hidden" name="group" value="">
					{*
					<div class="field group">
						<label for="group">##NEWS_GROUPS_DESTINATION## :</label>
						<select name="group" id="group">
						{include file="optiongrouptree.tpl"}
						</select>
					</div>
					*}
					<div class="button">
						<input type="submit" value="{if isset($theNewsToModify)}##POST_MODIFIED_ARTICLE##{else}##POST_NEW_ARTICLE##{/if}" />
					</div>
				</form>
	{else}
		<div class="error">
			##NO_RIGHT_TO_MODIFY##
		</div>
	{/if}
	</div>
</div>
