<h1>##SEARCH_RESULTS##</h1>
##SEARCH_RESULTS## ##FOR## <strong>{$keywords}</strong>
<ul class="articles">
{foreach from=$articles item=article}
    <li>
        <strong><a href="{kurl app="news" page="view" id=$article->getID() displayComments=1}">{$article->getTitle()|highlight:$keywords}</a></strong>
        <br />
        <em>{$article->getContent()|find:$keywords|highlight:$keywords}</em>
    </li>
{/foreach}
</ul>