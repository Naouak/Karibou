<div class="news">
	<h1>##NEWS##</h1>

	{if $permission > _READ_ONLY_}
			<strong><a href="{kurl page="add"}">##ADD_ARTICLE##</a></strong>
	{/if}
	
	{include file="newsmessage.tpl"}
	
	{if $permission > _DEFAULT_}
		{section name=i loop=$theNews step=1}
			{assign var="theArticle" value=$theNews[i]}
			{assign var="idNews" value=$theNews[i]->getID()}
			{assign var="theArticleComments" value=$theNewsComments[$idNews]}
			{include file="newsview.tpl"}
		{/section}
		
			{if ($pages|@count)>1}
			<p>
				{section name=p loop=$pages}
				 {if not $smarty.section.p.first}
				  |
				 {else}
					##PAGES## :
				 {/if}
				 <a href="{kurl pagenum="$pages[p]"}">{$pages[p]}</a>
				{/section}
			</p>
			{/if}
	{/if}
</div>