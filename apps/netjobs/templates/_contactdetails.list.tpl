{if ($myContact !== FALSE) && ($myContact|@count > 0)}
			<ul>
				<li class="firstname">
					<label for="firstname">##CONTACT_FIRSTNAME## :</label>
					<span name="firstname">{$myContact->getProfileInfo("firstname")}</span>
				</li>

				<li class="lastname">
					<label for="lastname">##CONTACT_LASTNAME## :</label>
					<span name="lastname">{$myContact->getProfileInfo("lastname")}</span>
				</li>

				{if $myContact->getProfileInfo("url") && ($myContact->getProfileInfo("url") != "")}
				<li class="lastname">
					<label for="lastname">##CONTACT_WEBSITE## :</label>
					<span name="lastname"><a href="{$myContact->getProfileInfo("url")}">{$myContact->getProfileInfo("url")}</a></span>
				</li>
				{/if}


				{if $myContact->getProfileInfo("note") && ($myContact->getProfileInfo("note") != "")}
				<li class="lastname">
					<label for="lastname">##CONTACT_NOTE## :</label>
					<span name="lastname">{$myContact->getProfileInfo("note")|escape}</span>
				</li>
				{/if}
			</ul>

	<div class="directory profile">
	{if ($myContact->getAddresses()|@count>0)}	
		<div class="addresses">
			<h3>##CONTACT_ADDRESSES##</h3>
		{foreach item=address from=$myContact->getAddresses()}
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
			<br class="clear" />
		</div>
	{/if}
	
	{if ($myContact->getPhones()|@count>0)}	
		<div class="phones">
			<h3>##CONTACT_PHONES##</h3>
		{foreach item=phone from=$myContact->getPhones()}
			<div class="phone">
				<h4>{translate key=$phone.type}</h4>
				{$phone.number}
			</div>
		{/foreach}
			<br class="clear" />
		</div>
	{/if}
	
	{if ($myContact->getEmails()|@count>0)}	
		<div class="emails">
			<h3>##CONTACT_EMAILS##</h3>
		{foreach item=email from=$myContact->getEmails()}
				<div class="email">
					<h4>{translate key=$email.type}</h4>
					<a href="mailto:{$email.email}">{$email.email}</a>
				</div>
		{/foreach}
			<br class="clear" />
		</div>
	{/if}
	</div>
{else}
	##CONTACT_UNKNOWN##
{/if}