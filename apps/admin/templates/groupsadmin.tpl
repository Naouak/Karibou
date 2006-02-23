
{include file="grouptree.tpl" grouptree=$grouptree key=0}

<form action="{kurl action='addgroup'}" method="POST">
	<label for="input_name">Name : </label>
	<input id="input_name" type="text" name="name" /><br />
	<label for="select_parent">Parent : </label>
	<select id="select_parent" name="parent">
	<option value="0">No Parent</option>
{include file="optiongrouptree.tpl" grouptree=$grouptree level=0}
	</select><br />
	<input type="submit" value="add" />
</form>