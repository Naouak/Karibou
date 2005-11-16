<h1>Import users</h1>
<div class="helper">
	This tool uses the database table admin_import.
	<ul>
		<li>Table structure : $firstname, $lastname, $group, $email, $phone, $login, $password, $done_date, $email_sent</li>
		<li>LDAP Import</li>
		<li>Karibou Import</li>
	</ul>
	<strong>Goals</strong>
	Use this table to:
	<ul>
		<li>Generate password</li>
		<li>Generate login</li>
		<li>Import in LDAP</li>
		<li>Import in Karibou</li>
	</ul>
</div>

<form action="{kurl page="importsave"}" method="POST">
	<textarea name="csv" rows="1" cols="20"></textarea>
	<input type="submit" name="ImportCSV" value="ImportCSV">
	<br />
{if ($rows) && ($rows|@count > 0)}

	<input type="submit" name="GeneratePasswords" value="GeneratePasswords">
	<input type="submit" name="GenerateLogins" value="GenerateLogins">
	<br />
	<input type="submit" name="FullImportLDAP" value="FullImportInLDAP">
	<br />
	<input type="submit" name="FullImportKaribou" value="FullImportKaribou">
{if (isset($check))}
	{if $check.keyerror}
		<div class="error">
			Problem in keys... Mandatory keys: firstname, lastname, group, email, phone.
		</div>
	{elseif ($check.loginexists)}
		<div class="error">
			User login already exists
		</div>
	{elseif ($check.addedink2)}
		<div class="success">
			User added in Karibou
		</div>

	{elseif (isset($check.email))}
			<div class="helper">
				<ul>
		{foreach from=$check.email item=item key=key}
					<li>
				{if $item === TRUE}
					OK:
				{else}
					KO:
				{/if}
					{$key}
					</li>
		{/foreach}
				</ul>
			</div>
	{elseif (isset($check.ldap))}
		{foreach from=$check.ldap item=item key=key}
			<div class="helper">
				<ul>
					<li>
				{if ($item === TRUE)}
					OK:
				{else}
					KO ({$item}):
				{/if}
					{$key}
					</li>
				</ul>
			</div>
		{/foreach}
	{else}
		{assign var="notadded" value=$check.total-$check.added}
		<div class="success">
			{$check.total} records proposed.
			{$check.added} records added (which means {$notadded} duplicate records not added).	
		</div>
	{/if}
{/if}

	<table border="1">
		<tr>
		{foreach from=$rows.0 key=key item=value}
			<th><strong>{$key}</strong></th>
		{/foreach}
			<th><strong>Karibou</strong></th>
			<th><strong>LDAP</strong></th>
			<th><strong>SendEmail</strong></th>
		</tr>
		{foreach from=$rows item=row}
		<tr>
			{foreach from=$row key=key item=value}
				{if $key == id}
					{if ($value == $editid)}
						{assign var="editmode" value=1}
						<input type="hidden" name="{$key}" value="{$value}">
					{else}
						{assign var="editmode" value=0}
					{/if}
				{/if}
			<td>
				{if $key == id}
					<a href="{kurl page="import" editid=$value}">{$value}</a>
				{else}
					{if ($editmode == 1)}
						<input type="text" name="{$key}" value="{$value}"/>
					{else}
						{$value}
					{/if}
				{/if}
			</td>
			{/foreach}
			<td>
				<input type="submit" name="ImportInKaribou" value="{$row.id}">
			</td>
			<td>
				<input type="submit" name="ImportInLDAP" value="{$row.id}">
			</td>
			<td>
				<input type="submit" name="SendEmail" value="{$row.id}">
			</td>
		</tr>
		{/foreach}	
	</table>
{if (isset($editid))}
	<input type="submit" name="ApplyModifications" value="ApplyModifications">
	(<a href="{kurl page="import"}">Quit edit mode</a>)
{/if}
{else}
	No import data
{/if}
</form>
