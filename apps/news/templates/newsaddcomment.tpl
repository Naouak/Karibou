{if $permission > _DEFAULT_}
	{include file="newsmessage.tpl"}
			{if isset($theArticle)}
				<div class="newNewsCommentForm">
					<div class="title">Ajouter un commentaire à l'article "{$theArticle->getTitle()}"</div>
					<form action="{kurl action="post"}" method="post">
						<input type="hidden" name="postType" value="newNewsComment">
						<input type="hidden" name="id_news" value="{$theArticle->getId()}" />
						<input type="hidden" name="id_parent" value="" />
						Titre: <input type="text" name="title" value="{if ($theArticle->getID()==$theNewsCurrentComment.newsId)}{$theNewsCurrentComment.title}{/if}"/><br />
						Description: <textarea name="content"/>{if ($theArticle->getId()==$theNewsCurrentComment.newsId)}{$theNewsCurrentComment.content}{/if}</textarea><br />
						<input type="submit" value="Poster un commentaire sur cet article" />
					</form>
				</div>
			{else}
				<div class="newNewsCommentForm">
					<div class="title">Aucun article n'a été trouvé.</div>
					L'article que vous recherchez n'a pas été trouvé.
				</div>
			{/if}
{else}
	Vous n'êtes pas autorisé à poster un commentaire.
{/if}