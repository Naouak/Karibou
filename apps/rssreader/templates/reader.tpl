<!-- {$title} -->

{foreach item=item from=$items}
<p><a href="{$item.link}">{$item.title|truncate:65}</a></p>
{/foreach}
