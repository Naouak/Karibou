<h1>##TITLE##</h1>
<h2>
	##USER_PROFILE## :
	{if ($profile.firstname != "" && $profile.lastname != "")}
	{$profile.firstname} {$profile.lastname} ({$username})
	{else}
	{$username}
	{/if}
</h2>
<div class="directory profile">
	{if ($picture != '')}
	<img src="{$picture}" />
	{else}
	{/if}
	<div class="infos">
		##FIRSTNAME## : <strong>{$profile.firstname}</strong><br />
		##LASTNAME## : <strong>{$profile.lastname}</strong><br />
		{if isset($profile.surname) && ($profile.surname != "")}##SURNAME## : <strong>{$profile.surname}</strong><br />{/if}
		<em>##LOGIN## : {$username}</em><br />
		{if isset($profile.url) && ($profile.url != "")}##WEBSITE## : <strong><a href="{$profile.url}">{$profile.url}</a></strong><br />{/if}
		{if isset($profile.note) && ($profile.note != "")}##NOTE## : <strong>{$profile.note|escape}</strong><br />{/if}
		{if isset($profile.birthday) && ($profile.birthday != "")}##SHOWBIRTHDATE## : <strong>{$profile.birthday|escape}</strong><br />{/if}
		{if isset($profile.company) && ($profile.company != "")}##COMPANY## : <strong>{$profile.company|escape}</strong><br />{/if}

	</div>
	<hr class="clear" />
	{if $edit}
	<div class="editprofilelink">
		<a href="{kurl username=$username act='edit'}">##EDIT_LINK##</a></strong>
	</div>
	{/if}
	
	{if !$noflashmail}
	<div class="sendflashmaillink">
		<a href="{kurl app="flashmail" page="writeto" userid=$user->getId()}" onclick="new Ajax.Updater('flashmail_headerbox_answer', '{kurl app="flashmail" page="headerbox_writeto" userid=$user->getId()}',{literal} {asynchronous:true, evalScripts:true}{/literal}); document.getElementById('flashmail_headerbox_answer').className='show'; return false;">##SEND_FLASHMAIL##</a>
	</div>
	{/if}
	{if ($addresses|@count>0)}	
	<div class="sendflashmaillink">
		<a href="{kurl app="geoloc" page="search" login=$username}">##PROFILE_GEOLOC##</a>
	</div>
	{/if}

	{hook name="directory_profile_cvlistmodule"}

	<div class="groups">
        {if $usergroups->count() gt 1 }
		<h3>##BELONGS_TO_GROUPS##</h3>
        {elseif $usergroups->count() eq 1 }
        <h3>##BELONGS_TO_GROUP##</h3>
        {/if}
		<ul>
			{include file="grouplist.tpl"}
		</ul>
	</div>

	
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
