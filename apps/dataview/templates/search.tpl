<h1>##DATAVIEW##</h1>
<div class="helper">##DATAVIEW_DETAILS##</div>
<div class="dataview">
	{if isset($search)}
		{$search}
	{/if}
	<form action="" method="POST">
		<input type="text" name="keyword"/>
		<input type="submit">
	</form>
	{include file="_listnavi.tpl"}
	{include file="_recordslist.tpl"}
	{include file="_listnavi.tpl"}
</div>