<h1>##TITLE##</h1>
<div class="directory search">
	<div class="helper">
		##DIRECTORY_DESCRIPTION##
	</div>
	
	<h3>##USER_SEARCH##</h3>
	
	<form>
		<label for="search">##SEARCH##</label> <input type="text" name="search" id="search" />
		<input type="submit" value="##SEARCH_TITLE##">
	</form>
	
	<p>
{if !isset($userlist)}
    {include file="grouptree.tpl"}
{else}
    {include file="_searchresults.tpl"}
{/if}
    </p>
</div>