<div class="search">
	<h1>##SEARCH##</h1>
	<form method="post">
		<input type="text" name="keywords" value="{if isset($keywords)}{$keywords}{/if}">
		##SEARCH_IN##
		<select name="app">
			<option value="everywhere" {if $app == 'everywhere'}selected{/if}>##INTRANET##</option>
			<option value="news" {if $app == 'news'}selected{/if}>##APP_NEWS##</option>
			<option value="fileshare" {if $app == 'fileshare'}selected{/if}>##APP_FILESHARE##</option>
			<option value="annuaire" {if $app == 'annuaire'}selected{/if}>##APP_ANNUAIRE##</option>
		</select>
		<input type="submit" value="##SEARCH_TITLE##">
	</form>

{if isset($articles) && count($articles) > 0 ||
 isset($files) && count($files) > 0 || 
isset($usersfound) && count($usersfound) > 0 }

<div class="goto">
	##SEARCH_GOTO## :

	{if isset($articles) && count($articles) > 0}
		<a href="{kurl app="search"}#search_news">{$articles|@count} {if count($articles)==1}##SEARCH_RESULT##{else}##RESULTS##{/if} ##SEARCH_IN## ##APP_NEWS##</a>
	{/if}
	
	{if isset($files) && count($files) > 0}
		<a href="{kurl app="search"}#search_fileshare">{$files|@count} {if count($files)==1}##SEARCH_RESULT##{else}##RESULTS##{/if} ##SEARCH_IN## ##APP_FILESHARE##</a>
	{/if}
	
	{if isset($usersfound) && count($usersfound) > 0}
		<a href="{kurl app="search"}#search_annuaire">{$usersfound|@count} {if count($usersfound)==1}##SEARCH_RESULT##{else}##RESULTS##{/if} ##SEARCH_IN## ##APP_ANNUAIRE##</a>
	{/if}
	
</div>
{/if}
<br style="clear: both;" />

{if isset($errors) && count($errors) > 0}

	<ul style="color: #900; list-style: none;">
		{foreach item=error from=$errors}
			<li>{$error}</li>
		{/foreach}
	</ul>

{else}

	{if !isset($articles)}
	{elseif count($articles) == 0}
		<div id="search_news" class="resultset">
		##SEARCH_NORESULT## ##SEARCH_IN## <strong>##NEWS##</strong>
		</div>
	{else}
		<div id="search_news" class="resultset">
		{$articles|@count} {if count($articles) == 1}##SEARCH_RESULT##{else}##RESULTS##{/if} ##SEARCH_IN## <strong>##NEWS##</strong>
		<ul class="articles">
		{foreach item=article from=$articles}
			<li>
				<div class="title"><a href="{kurl app="news" page="view" id=$article->getID() displayComments='1'}">{$article->getTitle()|highlight:$keywords}</a></div>
				<div class="content">{$article->getContent()|find_and_highlight:$keywords}</div>
				<div class="url">{$base_url}{kurl app="news" page="view" id=$article->getID() displayComments='1'}</div>
			</li>
		{/foreach}
		</ul>
		</div>
	{/if}
		
		
	{if !isset($files)}
	{elseif count($files) == 0}
		<div id="search_fileshare" class="resultset">
		##SEARCH_NORESULT## ##SEARCH_IN## <strong>##FILESHARE##</strong>
		</div>
	{else}
		<div id="search_fileshare" class="resultset">
		{$files|@count} {if count($files) == 1}##SEARCH_RESULT##{else}##RESULTS##{/if} ##SEARCH_IN## <strong>##FILESHARE##</strong>
		<ul class="files">
		{foreach item=file from=$files}
			<li>
				<div class="title"><a href="{kurl app='fileshare' page="details" elementpath=$file->getPathBase64()}">{$file->getName()|highlight:$keywords}</a></div>
				<div class="content">{$file->getLastVersionInfo('description')|find_and_highlight:$keywords}</div>
				<div class="url">{$base_url}{kurl app='fileshare' page="details" elementpath=$file->getPathBase64()}</div>
			</li>
		{/foreach}
		</ul>
		</div>
		
	{/if}

	{if !isset($usersfound)}
	{elseif count($usersfound) == 0}
		<div id="search_annuaire" class="resultset">
		##SEARCH_NORESULT## ##SEARCH_IN## <strong>##ANNUAIRE##</strong>
		</div>
	{else}
		<div id="search_annuaire" class="resultset">
		{$usersfound|@count} {if count($usersfound) == 1}##SEARCH_RESULT##{else}##RESULTS##{/if} ##SEARCH_IN## <strong>##ANNUAIRE##</strong>
		<div class="directory search">
		{foreach item=user from=$usersfound}
			<div class="thumbnail">
			<div class="image">
			<a href="annuaire/{$user->getLogin()}"  title="{$user->getFirstName()} {$user->getLastname()}"><img src="{$user->getPicturePath()}" /></a>
			</div>
			<div class="title">
				<a href="annuaire/{$user->getLogin()}" title="{$user->getFirstName()} {$user->getLastname()}">
	
			{*Nickname, Name, login*}
			{if $user->getSurname()}
				<div>{$user->getSurname()}</div>
			{/if}
			{if $user->getFirstName()} 
				<div>{$user->getFirstname()} {$user->getLastname()}</div>
			{/if}
			{if !$user->getSurname() && !$user->getFirstName()}
				<div>{$user->getLogin()}</div>
			{/if}
	
			</a>
			</div>
			</div>
		{/foreach}
	
	
	</div>
	</div>
	{/if}
	

{/if}
</div>
