<h1>##NETJOBS_TITLE##</h1>
<h3>##JOB_CONTACTDETAILS##</h3>
<div class="netjobs">
{if ($myContact !== FALSE) && ($myContact|@count > 0)}
<div class="directory profile">
	<div class="infos">
		##CONTACT_FIRSTNAME## : <strong>{$myContact.profile.firstname}</strong><br />
		##CONTACT_LASTNAME## : <strong>{$myContact.profile.lastname}</strong><br />
		{if isset($myContact.profile.url) && ($myContact.profile.url != "")}##CONTACT_WEBSITE## : <strong><a href="{$myContact.profile.url}">{$myContact.profile.url}</a></strong><br />{/if}
		{if isset($myContact.profile.note) && ($myContact.profile.note != "")}##CONTACT_NOTE## : <strong>{$myContact.profile.note|escape}</strong><br />{/if}
	</div>
	<hr class="clear" />
{if $edit}
&nbsp;<strong><a href="{kurl app="addressbook" page="editnj" profile_id=$myContact.profile.id}">##CONTACT_EDIT_LINK##</a></strong>{/if}

{if ($myContact.addresses|@count>0)}	
	<div class="addresses">
		<h3>##CONTACT_ADDRESSES##</h3>
	{foreach item=address from=$myContact.addresses}
		<div class="address">
			<h4>{translate key=$address.type}</h4>
			{if ($address.poaddress != "")}	{$address.poaddress}<br />{/if}
			{if ($address.extaddress != "")}	{$address.extaddress}<br />{/if}
			{if ($address.street != "")}		{$address.street}<br />{/if}
			{if ($address.postcode != '' || $address.city != '')}
					{$address.postcode}	{$address.city}<br />
			{/if}
			{if ($address.country != "")}		{$address.country} {/if}
		</div>
	{/foreach}
		<hr class="clear" />
	</div>
{/if}

{if ($myContact.phones|@count>0)}	
	<div class="phones">
		<h3>##CONTACT_PHONES##</h3>
	{foreach item=phone from=$myContact.phones}
		<div class="phone">
			<h4>{translate key=$phone.type}</h4>
			{$phone.number}
		</div>
	{/foreach}
		<hr class="clear" />
	</div>
{/if}

{if ($myContact.emails|@count>0)}	
	<div class="emails">
		<h3>##CONTACT_EMAILS##</h3>
	{foreach item=email from=$myContact.emails}
			<div class="email">
				<h4>{translate key=$email.type}</h4>
				<a href="mailto:{$email.email}">{$email.email}</a>
			</div>
	{/foreach}
		<hr class="clear" />
	</div>
{/if}
</div>


	{*
	<div class="contactdetails">
			<div class="name">
				<label for="type">##CONTACT_NAME## :</label>
				<span name="type">{$myContact.profile.firstname} {$myContact.profile.lastname}</span>
			</div>
		
	{if ($myContact.addresses|@count>0)}	
		<fieldset class="addresses">
			<legend>##ADDRESSES##</legend>
		{foreach item=address from=$myContact.addresses}
			<div class="address">
				<h4>{translate key=$address.type}</h4>
				{if ($address.poaddress != "")}	{$address.poaddress}<br />{/if}
				{if ($address.extaddress != "")}	{$address.extaddress}<br />{/if}
				{if ($address.street != "")}		{$address.street}<br />{/if}
				{if ($address.postcode != '' || $address.city != '')}
						{$address.postcode}	{$address.city}<br />
				{/if}
				{if ($address.country != "")}		{$address.country} {/if}
			</div>
		{/foreach}
			<hr class="clear" />
		</fieldset>
	{/if}
	
	{if ($myContact.phones|@count>0)}	
		<fieldset class="phones">
			<legend>##PHONES##</legend>
		{foreach item=phone from=$myContact.phones}
			<div class="phone">
				<h4>{translate key=$phone.type}</h4>
				{$phone.number}
			</div>
		{/foreach}
			<hr class="clear" />
		</fieldset>
	{/if}
	
	{if ($myContact.emails|@count>0)}
		<fieldset class="emails">
			<legend>##EMAILS##</legend>
		{foreach item=email from=$myContact.emails}
				<div class="email">
					<h4>{translate key=$email.type}</h4>
					<a href="mailto:{$email.email}">{$email.email}</a>
				</div>
		{/foreach}
			<hr class="clear" />
		</fieldset>
	{/if}


	</div>
	*}
{else}
	##CONTACT_UNKNOWN##
{/if}
</div>