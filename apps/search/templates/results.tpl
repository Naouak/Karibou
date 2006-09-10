<h1>##SEARCH_SEARCHRESULTS##</h1>
{if ($errors|@count) > 0}
{*##SEARCH_RESULTSS## ##FOR##*} <strong>{$keywords}</strong>
<ul style="color: #900; list-style: none;">
	{foreach from=$errors item="error"}
		<li>{$error}</li>
	{/foreach}
</ul>
{else}
	{if $articles|@count == 0}
		##SEARCH_NORESULT##
	{else}
		{$articles|@count} ##SEARCH_RESULTSS## ##SEARCH_IN## ##NEWS##
		<ul class="articles">
		{foreach from=$articles item=article}
			<li>
				<strong><a href="{kurl app="news" page="view" id=$article->getID() displayComments=1}">{$article->getTitle()|highlight:$keywords}</a></strong>
				<br />
				<em>{$article->getContent()|find:$keywords|highlight:$keywords}</em>
			</li>
		{/foreach}
		</ul>
	{/if}
{/if}