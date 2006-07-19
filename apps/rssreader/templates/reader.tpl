<h3 class="handle">{$title}</h3>
{foreach item=item from=$items}
<p><a href="{$item.link}">{$item.title|truncate:65}</a></p>
{/foreach}
