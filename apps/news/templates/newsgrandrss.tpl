{insert name=header content="Content-Type: application/xml"}<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
	<channel>
		<title>Karibou :: News</title>
		<link>{kurl app="news" page=""}</link>
		<description>Les news de Karibou</description>
		<language>fr</language>
		<pubDate>{$smarty.now|date_format:"%Y/%m/%d %H:%M:%S"}</pubDate>
		<lastBuildDate>{$smarty.now|date_format:"%Y/%m/%d %H:%M:%S"}</lastBuildDate>
		<copyright>Copyright 2005</copyright>
		<generator>Karibou</generator>
{section name=i loop=$theNews step=1}
{assign var="idNews" value=$theNews[i]->getID()}
	<item>
			<title>({$theNews[i]->getDate()}) {$theNews[i]->getTitle()} ({$theNewsComments[$idNews]|@count} commentaire{if (($theNewsComments[$idNews]|@count)>1)}s{/if})</title>
			<link>{kurl server=$servername app="news" page="view" ArticleId=$theNews[i]->getID() DisplayComments="1"}</link>
		</item>
{/section}
		<item>
			<title>Ajouter un article</title>
			<link>{kurl page="add"}</link>
		</item>
	</channel>
</rss>