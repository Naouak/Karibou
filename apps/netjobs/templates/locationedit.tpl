<script>
function update_county()
{ldelim}
	var fieldset = document.getElementById('county');
	var country_id = document.getElementById('country_id').value;
	
	var html = '';
	
	if (country_id == '99189')
	{ldelim}
		html += '<label for="locationinfos[county_id]">##JOB_LOCATION_COUNTY_NAME## : </label>';
		html += '<input type="hidden" name="locationinfos[county_name]" value="">';
		{if isset($myJob) && $myJob->getLocationInfo("county_id") != ""}{assign var=jobcountyid value=$myJob->getLocationInfo("county_id")}{else}{/if}
		html += '<select name="locationinfos[county_id]">';
		html += '	<option DISABLED>##JOB_LOCATION_COUNTY_CHOOSE##</option>';
			{foreach from=$counties item="county"}
		html += '	<option value="{$county.id}"{if $jobcountyid == $county.id} SELECTED{/if}>{$county.name|escape:"htmlall"}</option>';
			{/foreach}
		html += '</select>';
	{rdelim}
	else
	{ldelim}
		html += '<label for="locationinfos[county_name]">##JOB_LOCATION_COUNTY_NAME## : </label>';
		html += '<input type="hidden" name="locationinfos[county_id]" value="">';
		html += '<input type="text" name="locationinfos[county_name]" value="{if isset($myJob) && $myJob->getLocationInfo("county_name") != ""}{$myJob->getLocationInfo("county_name")|escape:"quotes"}{/if}">';
	{rdelim}
	
	fieldset.innerHTML = html;
{rdelim}
</script>

<h1>##NETJOBS_TITLE##</h1>
{*<h2>{if (isset($myJob))}##JOB_UPDATE##{else}##JOB_ADDING##{/if}</h2>*}
<h3>##JOB_LOCATION_ADD## &quot;{$myJob->getInfo("title")}&quot;</h3>
<div class="netjobs">
	<div class="helper">
		##JOB_LOCATION_DESCRIPTION##
	</div>

	<form action="{kurl action="locationsave"}" method="post" class="jobedit" name="location" id="location">
		{if isset($myLocation)}
			<input type="hidden" name="locationinfos[country_id]"		value="{$myLocation->getCountryInfo("id")}">
			<input type="hidden" name="locationinfos[county_id]"		value="{$myLocation->getCountyInfo("id")}">
			<input type="hidden" name="locationinfos[department_id]"	value="{$myLocation->getDepartmentInfo("id")}">
			<input type="hidden" name="locationinfos[city_id]"			value="{$myLocation->getCityInfo("id")}">
		{/if}
		{if (isset($myJob))}
			<input type="hidden" name="jobid" value="{$myJob->getInfo("id")}">
		{/if}

		<fieldset>
			<legend>##JOB_LOCATION##</legend>
			<ul>
				<li class="country">
					<label for="locationinfos[country_id]">##JOB_LOCATION_COUNTRY## :</label>
					{if isset($myJob) && $myJob->getLocationInfo("country_id") != ""}{assign var=jobcountryid value=$myJob->getLocationInfo("country_id")}{else}{*France = Default*}{assign var=jobcountryid value="99189"}{/if}
					<select name="locationinfos[country_id]" id="country_id" onChange="update_county()">
						<option DISABLED>##JOB_LOCATION_COUNTRY_CHOOSE##</option>
						{foreach from=$countries item="country"}
							<option value="{$country.id}"{if $jobcountryid == $country.id} SELECTED{/if}>{$country.name|escape:"htmlall"}</option>
						{/foreach}
					</select>
				</li>
				
				<li class="county" id="county">
					<noscript>
							<label for="locationinfos[county_name]">##JOB_LOCATION_COUNTY_NAME## :</label>
							{if isset($myJob) && $myJob->getLocationInfo("county_name") != ""}{assign var=jobcountyname value=$myJob->getLocationInfo("county_name")}{/if}
							<input type="text" name="locationinfos[county_name]" value="{$jobcountyname}">
					</noscript>
				</li>
				<script>
					update_county();
				</script>
				
				{*
				<li class="department">
					<label for="locationinfos[department_id]">##JOB_LOCATION_DEPARTMENT## :</label>
					{if isset($myJob) && $myJob->getLocationInfo("department_id") != ""}{assign var=jobdepartmentid value=$myJob->getLocationInfo("department_id")}{/if}
					<select name="locationinfos[department_id]">
						<option DISABLED>##JOB_LOCATION_DEPARTMENT_CHOOSE##</option>
						{foreach from=$countries item="department"}
							<option value="{$department.id}"{if $jobdepartmentid == $department.id} SELECTED{/if}>{$department.name|escape:"htmlall"}</option>
						{/foreach}
					</select>
				</li>
				*}
				
				<li class="city_name">
					<label for="locationinfos[city_name]">##JOB_LOCATION_CITY_NAME## :</label>
					{if isset($myJob) && $myJob->getLocationInfo("city_name") != ""}{assign var=jobcityname value=$myJob->getLocationInfo("city_name")}{/if}
					<input type="text" name="locationinfos[city_name]" value="{$jobcityname}">
				</li>
			</ul>
		</fieldset>
		<div class="button">
			<input type="submit" value="{if isset($myLocation)}##JOB_LOCATION_SAVE##{else}##JOB_LOCATION_CREATE##{/if}" />
		</div>
	</form>
</div>