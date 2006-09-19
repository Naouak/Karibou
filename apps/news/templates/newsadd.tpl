{if $permission > _SELF_WRITE_}
	<h1>##NEWS##</h1>
	<a href="{kurl app="wiki" page="help"}" >##TITLE_WIKI_SYNTAX##</a>
	<div class="newNewsForm">
		<h1>Ajouter une News</h1>
		<form action="{kurl action="post"}" method="post">
			<input type="hidden" name="postType" value="newNews" />
			Titre: <input type="text" name="title" width="50"/><br />
			Description: <textarea name="content" rows="20" cols="60"/></textarea><br />
			<input type="submit" value="Poster une nouvelle News" />
		</form>
	</div>
{/if}