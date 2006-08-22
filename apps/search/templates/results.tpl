<h1>##SEARCH_RESULTS##</h1>
<ul class="articles">
{foreach from=$articles item=article}
    <li><strong><a href="{kurl app="news" page="view" id=$article->getID() displayComments=1}">{$article->getTitle()}</a></strong></li>
{/foreach}
</ul>