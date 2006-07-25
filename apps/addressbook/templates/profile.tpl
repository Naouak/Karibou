<h1>##ADDRESSBOOK_TITLE##</h1>
<h2>
	##PROFILE_INFO## :
	{if ($profile.firstname != "" && $profile.lastname != "")}
	{$profile.firstname} {$profile.lastname}
	{/if}
</h2>
<div class="profile">
{if $edit}
{/if}
	<img src="{$picture}" />
	<div class="infos">
		##FIRSTNAME## : <strong>{$profile.firstname}</strong><br />
		##LASTNAME## : <strong>{$profile.lastname}</strong><br />
		{if isset($profile.surname) && ($profile.surname != "")}##SURNAME## : <strong>{$profile.surname}</strong><br />{/if}
		{if isset($profile.url) && ($profile.url != "")}##WEBSITE## : <strong><a href="{$profile.url}">{$profile.url}</a></strong><br />{/if}
		{if isset($profile.note) && ($profile.note != "")}##NOTE## : <strong>{$profile.note|escape}</strong><br />{/if}
	</div>
	<hr class="clear" />
	{if $edit}
&nbsp;<strong><a href="{kurl profile_id=$profile.id act='edit'}">##EDIT_LINK##</a></strong>
	{/if}
{if ($addresses|@count>0)}	
	<div class="addresses">
		<h3>##ADDRESSES##</h3>
	{foreach item=address from=$addresses}
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

{if ($phones|@count>0)}	
	<div class="phones">
		<h3>##PHONES##</h3>
	{foreach item=phone from=$phones}
		<div class="phone">
			<h4>{translate key=$phone.type}</h4>
			{$phone.number}
		</div>
	{/foreach}
		<hr class="clear" />
	</div>
{/if}

{if ($emails|@count>0)}	
	<div class="emails">
		<h3>##EMAILS##</h3>
	{foreach item=email from=$emails}
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
<img src="{$picture}" /><br />
{if $edit}
<a href="{kurl page='' profile_id=$profile.id act=edit}">##EDIT_LINK##</a>
{/if}
<br />
##LASTNAME##{$profile.lastname}<br />
##FIRSTNAME##{$profile.firstname}<br />
<br />
<br />

{foreach item=address from=$addresses}
{translate key=$address.type} : <br />
{$address.poaddress}<br />
{$address.extaddress}<br />
{$address.street}<br />
{$address.postcode} {$address.city}<br />
{$address.country}<br />
{/foreach}
<br />
{foreach item=phone from=$phones}
{translate key=$phone.type} : {$phone.number}<br />
{/foreach}
<br />
{foreach item=email from=$emails}
{translate key=$email.type} : {$email.email}<br />
{/foreach}
<br />
*}