<h1>##TITLE##</h1>
<div class="directory">
{if $edit}
<h2>
	##USER_PROFILE_EDIT## :
	{if ($profile.firstname != "" && $profile.lastname != "")}
	{$profile.firstname} {$profile.lastname} ({$username})
	{else}
	{$username}
	{/if}
</h2>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={$gkey}"
            type="text/javascript"></script>
<script>
var address_next_id = 1;
var phone_next_id = 1;
var email_next_id = 1;
var geocoder = new GClientGeocoder();

function add_address()
{literal}
{
{/literal}
	var fieldset = document.getElementById('addresses_div');
	
	var html = '';

	html += '<br class="spacer" />';	
	html += '<hr />';	
	html += '<label for="address'+ address_next_id +'_type">##ADDRESSTYPE##</label>';
	html += '<select id="address'+ address_next_id +'_type" ';
	html += ' name="address'+ address_next_id +'_type">';
{foreach item=type from=$addr_types}
	html += '<option value="{$type}">{translate key=$type}</option>';
{/foreach}
	html += '</select><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_poaddress">##POADDRESS##</label>';
	html += '<input id="address'+ address_next_id +'_poaddress" type="text" ';
	html += ' name="address'+ address_next_id +'_poaddress" /><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_extaddress">##EXTADDRESS##</label>';
	html += '<input id="address'+ address_next_id +'_extaddress" type="text" ';
	html += ' name="address'+ address_next_id +'_extaddress" /><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_street">##STREET##</label>';
	html += '<input id="address'+ address_next_id +'_street" type="text" ';
	html += ' name="address'+ address_next_id +'_street" /><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_city">##CITY##</label>';
	html += '<input id="address'+ address_next_id +'_city" type="text" ';
	html += ' name="address'+ address_next_id +'_city" /><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_region">##REGION##</label>';
	html += '<input id="address'+ address_next_id +'_region" type="text" ';
	html += ' name="address'+ address_next_id +'_region" /><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_postcode">##POSTCODE##</label>';
	html += '<input id="address'+ address_next_id +'_postcode" type="text" ';
	html += ' name="address'+ address_next_id +'_postcode" /><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_country">##COUNTRY##</label>';
	html += '<input id="address'+ address_next_id +'_country" type="text" ';
	html += ' name="address'+ address_next_id +'_country" /><br class="spacer" />';
	html += '<label for="address'+ address_next_id +'_coords">##POSITION##</label>';
	html += '<input id="address'+ address_next_id +'_coords" type="text"';
	html += 'name="address'+ address_next_id +'_coords" disabled="disabled"/>';
	html += '<input type="button" value="##GEOCALC##" onclick="geocalc(\'address'+address_next_id+'\');" /><br class="spacer" />';
	fieldset.innerHTML += html;
	address_next_id++;
{literal}
}
{/literal}


function add_phone()
{literal}
{
{/literal}
	var fieldset = document.getElementById('phones_div');
	
	var html = '';

	html += '<br class="spacer" />';	
	html += '<hr />';	
	html += '<label for="phone'+ phone_next_id +'_type">##PHONETYPE##</label>';
	html += '<select id="phone'+ phone_next_id +'_type" ';
	html += ' name="phone'+ phone_next_id +'_type">';
{foreach item=type from=$phone_types}
	html += '<option value="{$type}">{translate key=$type}</option>';
{/foreach}
	html += '</select><br class="spacer" />';
	html += '<label for="phone'+ phone_next_id +'_number">##PHONENUMBER##</label>';
	html += '<input id="phone'+ phone_next_id +'_number" type="text" ';
	html += ' name="phone'+ phone_next_id +'_number" /><br class="spacer" />';
	
	fieldset.innerHTML += html;
	phone_next_id++;
	
{literal}
}
{/literal}

function add_email()
{literal}
{
{/literal}
	var fieldset = document.getElementById('emails_div');
	
	var html = '';

	html += '<br class="spacer" />';	
	html += '<hr />';	
	html += '<label for="email'+ email_next_id +'_type">##EMAILTYPE##</label>';
	html += '<select id="email'+ email_next_id +'_type" ';
	html += ' name="email'+ email_next_id +'_type">';
{foreach item=type from=$email_types}
	html += '<option value="{$type}">{translate key=$type}</option>';
{/foreach}
	html += '</select><br class="spacer" />';
	html += '<label for="email'+ email_next_id +'_email">##EMAILADDRESS##</label>';
	html += '<input id="email'+ email_next_id +'_email" type="text" ';
	html += ' name="email'+ email_next_id +'_email" /><br class="spacer" />';
		
	fieldset.innerHTML += html;
	email_next_id++;
	
{literal}
}
{/literal}

function getaddress(name)
{literal}
{
	var elts = new Array("street", "extaddress", "city", "postcode", "country");
	var ret = "";
	for(var i = 0; i < elts.length; i++){
		elt = name+"_"+elts[i];
		ret += document.getElementById(elt).value+" ";
	}
	return ret;
}
{/literal}

function geocalc(name)
{literal}
{
	geocoder.getLatLng(getaddress(name),function (point){coords = point.toString();document.getElementById(name+"_coords").value = coords.substring(1,coords.length-1);});
}
{/literal}
</script>

<div class="directory">
	<form action="{kurl action='modifyprofile'}" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="username" value="{$username}" />
		<fieldset class="largefieldset">
			<legend>##PROFILE_INFO##</legend>
			{if !isset($profile.firstname) || $profile.firstname == ""}
			<label for="firstname">##FIRSTNAME##</label>
				<input id="firstname" type="text" name="firstname" value="{$profile.firstname}" /><br class="spacer" />
			{else}
				<label for="firstname">##FIRSTNAME##</label>
				<strong>{$profile.firstname}</strong><br />
				<input type="hidden" name="firstname" value="{$profile.firstname}">
			{/if}
			{if !isset($profile.lastname) || $profile.lastname == ""}
			<label for="lastname">##LASTNAME##</label>
				<input id="lastname" type="text" name="lastname" value="{$profile.lastname}" /><br class="spacer" />
			{else}
				<label for="lastname">##LASTNAME##</label>
				<strong>{$profile.lastname}</strong><br />
				<input type="hidden" name="lastname" value="{$profile.lastname}">
			{/if}
			{if (!isset($nonickname) || ($nonickname === FALSE))}
			<label for="surname">##SURNAME##</label>
				<input id="surname" type="text" name="surname" value="{$profile.surname}" /><br class="spacer" />
			{else}
				<input type="hidden" name="surname" value="" />
			{/if}
			<label for="birthday">##BIRTHDATE##</label>
				<input id="birthday" type="text" name="birthday" value="{$profile.birthday}" /><br class="spacer" />
			<label for="url">##URL##</label>
				<input id="url" type="text" name="url" value="{$profile.url}" /><br class="spacer" />
			<label for="note">##NOTE##</label>
				<textarea id="note" name="note">{$profile.note}</textarea><br class="spacer" />
			<label for="company">##COMPANY##</label>
				<input id="company" type="text" name="company" value="{$profile.company}" /><br class="spacer" />
			<label for="picture">##PICTURE##</label>
				<input id="picture" type="file" name="picture" /><br class="spacer" />
				<span class="note">##PICTURE_NOTE##</span>
		</fieldset>
		
		
		<fieldset id="addresses_fieldset" class="largefieldset">
			<legend>##ADDRESSES##</legend>
		{foreach name="addr" item=address from=$addresses}
		{assign var="addr_it" value=$smarty.foreach.addr.iteration}
			<label for="address{$addr_it}_type">##TYPE## : </label>
			<select id="address{$addr_it}_type" 
				name="address{$addr_it}_type">
		{foreach item=type from=$addr_types}
				<option value="{$type}" {if $type==$address.type} SELECTED{/if}>{translate key=$type}</option>
		{/foreach}
			</select><br class="spacer" />
			<label for="address{$addr_it}_poaddress">##POADDRESS##</label>
				<input id="address{$addr_it}_poaddress" type="text"
					name="address{$addr_it}_poaddress" value="{$address.poaddress}" /><br class="spacer" />
			<label for="address{$addr_it}_extaddress">##EXTADDRESS##</label>
				<input id="address{$addr_it}_extaddress" type="text"
					name="address{$addr_it}_extaddress" value="{$address.extaddress}" /><br class="spacer" />
			<label for="address{$addr_it}_street">##STREET##</label>
				<input id="address{$addr_it}_street" type="text"
					name="address{$addr_it}_street" value="{$address.street}" /><br class="spacer" />
			<label for="address{$addr_it}_city">##CITY##</label>
				<input id="address{$addr_it}_city" type="text"
					name="address{$addr_it}_city" value="{$address.city}" /><br class="spacer" />
			<label for="address{$addr_it}_region">##REGION##</label>
				<input id="address{$addr_it}_region" type="text"
					name="address{$addr_it}_region" value="{$address.region}" /><br class="spacer" />
			<label for="address{$addr_it}_postcode">##POSTCODE##</label>
				<input id="address{$addr_it}_postcode" type="text"
					name="address{$addr_it}_postcode" value="{$address.postcode}" /><br class="spacer" />
			<label for="address{$addr_it}_country">##COUNTRY##</label>
				<input id="address{$addr_it}_country" type="text"
					name="address{$addr_it}_country" value="{$address.country}" /><br class="spacer" />
			<label for="address{$addr_it}_coords">##COORDS##</label>
				<input id="address{$addr_it}_coords" type="text"
					name="address{$addr_it}_coords" value="{$address.coords}" /><input type="button" value="##GEOCALC##" onclick="geocalc('address{$addr_it}');" /><br class="spacer" />
			<label for="address{$addr_it}_delete">##DELETE##</label>
				<input id="address{$addr_it}_delete" type="checkbox" class="checkbox"
					name="address{$addr_it}_delete" value="delete" /><br class="spacer" />
		<hr />
		{/foreach}
		
		<script>
		address_next_id = {$addr_it+1} ;
		document.write('<input type="button" class="button" onclick="add_address()" value="##ADD_AN_ADDRESS##" /><br class="spacer" />');
		</script>
		
		<noscript>
			##CREATE_NEW_ENTRY## :<br />
			<label for="address{$addr_it+1}_type">##ADDRESSTYPE##</label>
			<select id="address{$addr_it+1}_type" 
				name="address{$addr_it+1}_type">
		{foreach item=type from=$addr_types}
				<option value="{$type}">{translate key=$type}</option>
		{/foreach}
			</select><br />
			<label for="address{$addr_it+1}_poaddress">##POADDRESS##</label>
				<input id="address{$addr_it+1}_poaddress" type="text"
					name="address{$addr_it+1}_poaddress" /><br class="spacer" />
			<label for="address{$addr_it+1}_extaddress">##EXTADDRESS##</label>
				<input id="address{$addr_it+1}_extaddress" type="text"
					name="address{$addr_it+1}_extaddress" /><br class="spacer" />
			<label for="address{$addr_it+1}_street">##STREET##</label>
				<input id="address{$addr_it+1}_street" type="text"
					name="address{$addr_it+1}_street" /><br class="spacer" />
			<label for="address{$addr_it+1}_city">##CITY##</label>
				<input id="address{$addr_it+1}_city" type="text"
					name="address{$addr_it+1}_city" /><br class="spacer" />
			<label for="address{$addr_it+1}_region">##REGION##</label>
				<input id="address{$addr_it+1}_region" type="text"
					name="address{$addr_it+1}_region" /><br class="spacer" />
			<label for="address{$addr_it+1}_postcode">##POSTCODE##</label>
				<input id="address{$addr_it+1}_postcode" type="text"
					name="address{$addr_it+1}_postcode" /><br class="spacer" />
			<label for="address{$addr_it+1}_country">##COUNTRY##</label>
				<input id="address{$addr_it+1}_country" type="text"
					name="address{$addr_it+1}_country" /><br class="spacer" />
			<label for="address{$addr_it+1}_coords">##POSITION##</label>
				<input id="address{$addr_it+1}_coords" type="text"
					name="address{$addr_it+1}_coords" value="{$address.coords}" disabled="disabled"/><input type="button" value="##GEOCALC##" onclick="geocalc('address{$addr_it+1}');" /><br class="spacer" />
		</noscript>
		<div id="addresses_div"></div>
		</fieldset>
		
		<fieldset id="phones_fieldset" class="largefieldset">
			<legend>##PHONES##</legend>
		{foreach name="phone" item=phone from=$phones}
		{assign var="phone_it" value=$smarty.foreach.phone.iteration}
			<label for="phone{$phone_it}_type">##PHONETYPE##</label>
			<select id="phone{$phone_it}_type" 
				name="phone{$phone_it}_type">
		{foreach item=type from=$phone_types}
				<option value="{$type}" {if $type==$phone.type} SELECTED{/if}>{translate key=$type}</option>
		{/foreach}
			</select><br class="spacer" />
			<label for="phone{$phone_it}_number">##PHONENUMBER##</label>
				<input id="phone{$phone_it}_number" type="text"
					name="phone{$phone_it}_number" value="{$phone.number}" /><br class="spacer" />
			<label for="phone{$phone_it}_delete">##DELETE##</label>
				<input id="phone{$phone_it}_delete" type="checkbox" class="checkbox"
					name="phone{$phone_it}_delete" value="delete" /><br class="spacer" />
		<hr />
		{/foreach}
		
		<script>
		phone_next_id = {$phone_it+1} ;
		document.write('<input type="button" class="button" onclick="add_phone()" value="##ADD_A_PHONE_NUMBER##" /><br class="spacer" />');
		</script>
		
		<noscript>
			Create a new Entry here :<br class="spacer" />
			<label for="phone{$phone_it+1}_type">##PHONETYPE##</label>
			<select id="phone{$phone_it+1}_type" 
				name="phone{$phone_it+1}_type">
		{foreach item=type from=$phone_types}
				<option value="{$type}">{translate key=$type}</option>
		{/foreach}
			</select><br class="spacer" />
			<label for="phone{$phone_it+1}_number">##PHONENUMBER##</label>
				<input id="phone{$phone_it+1}_number" type="text"
					name="phone{$phone_it+1}_number" /><br class="spacer" />
		</noscript>
		<div id="phones_div"></div>
		</fieldset>
		
		<fieldset id="emails_fieldset" class="largefieldset">
			<legend>##EMAILS##</legend>
		{foreach name="email" item=email from=$emails}
		{assign var="email_it" value=$smarty.foreach.email.iteration}
			<label for="email{$email_it}_type">##EMAILTYPE##</label>
			<select id="email{$email_it}_type" 
				name="email{$email_it}_type">
		{foreach item=type from=$email_types}
				<option value="{$type}" {if $type==$email.type} SELECTED{/if}>{translate key=$type}</option>
		{/foreach}
			</select><br class="spacer" />
			<label for="email{$email_it}_email">##EMAILADDRESS##</label>
				<input id="email{$email_it}_email" type="text"
					name="email{$email_it}_email" value="{$email.email}" /><br class="spacer" />
			<label for="email{$email_it}_delete">##DELETE##</label>
				<input id="email{$email_it}_delete" type="checkbox" class="checkbox"
					name="email{$email_it}_delete" value="delete" /><br class="spacer" />
		<hr />
		{/foreach}
		
		<script>
		email_next_id = {$email_it+1} ;
		document.write('<input type="button" class="button" onclick="add_email()" value="##ADD_AN_EMAIL##" /><br class="spacer" />');
		</script>
		
		<noscript>
			<label for="email{$email_it+1}_type">##EMAILTYPE##</label>
			<select id="email{$email_it+1}_type" 
				name="email{$email_it+1}_type">
		{foreach item=type from=$email_types}
				<option value="{$type}">{translate key=$type}</option>
		{/foreach}
			</select><br class="spacer" />
			<label for="email{$email_it+1}_email">##EMAILADDRESS##</label>
				<input id="email{$email_it+1}_email" type="text"
					name="email{$email_it+1}_email" /><br class="spacer" />
		</noscript>
		<div id="emails_div"></div>
		</fieldset>
		<input type="submit" name="save" value="##SAVE_PROFILE##" class="saveprofile" />
		<br class="spacer" />
	</form>
</div>
{else}
<p>##NO_ACCESS##</p>
{/if}
</div>
