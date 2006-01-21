<h1>##DATAVIEW##</h1>
<div class="helper">##DATAVIEW_DETAILS##</div>
<div class="dataview">
	<form action="" method="POST">
		<label for="keyword">##DV_SEARCH## :</label>
		<input type="text" name="keyword"/>
		<input type="submit">
	</form>
	{if $nbrecords >= 1}
		{$nbrecords} {if ($records|@count)==1}##DV_RESULT##{else}##DV_RESULTS##{/if} ##DV_FORSEARCHKEYWORD## &quot;<strong>{$keyword}</strong>&quot;
		{include file="_listnavi.tpl"}
		{include file="_recordslist.tpl"}
		{include file="_listnavi.tpl"}
	{elseif (isset($keyword)) && $keyword != ""}
		##DV_NOSEARCHRESULTSFOR## &quot;<strong>{$keyword}</strong>&quot;
	{/if}
</div>