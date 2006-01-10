<h1>##NETJOBS##</h1>
{if (isset($myJob))}
<h2>##JOB_ADDING## {$myJob->getInfo("title")}</h2>
<h3>##LOCATIONADD##</h3>
{else}
<h2>{if isset($myLocation)}##LOCATIONMODIFY##{else}##LOCATIONADD##{/if}</h2>
{/if}
<div class="netjobs">
	<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	<form action="{kurl action="locationsave"}" method="post" class="jobedit">
		{if isset($myLocation)}
			<input type="hidden" name="locationinfos[country_id]" value="{$myLocation->getCountryInfo("id")}">
			<input type="hidden" name="locationinfos[county_id]" value="{$myLocation->getCountyInfo("id")}">
			<input type="hidden" name="locationinfos[department_id]" value="{$myLocation->getDepartmentInfo("id")}">
			<input type="hidden" name="locationinfos[city_id]" value="{$myLocation->getCityInfo("id")}">
		{/if}
		{if (isset($myJob))}
			<input type="hidden" name="jobid" value="{$myJob->getInfo("id")}">
		{/if}

		<fieldset>
			<legend>##JOB_LOCATION##</legend>
			<ul>
				<li class="country">
					<label for="country">##JOBCOUNTRY## :</label>
					{if isset($myJob)}{assign var=jobcountryid value=$myJob->getInfo("country_id")}{else}{*France = Default*}{assign var=jobcountryid value="99189"}{/if}
					<select name="locationinfos[country_id]">
						<option DISABLED>##JOBCHOOSECOUNTRY##</option>
						{foreach from=$countries item="country"}
							<option value="{$country.id}"{if $myLocation->getCountryInfo("id") == $country.id} SELECTED{/if}>{$country.name|escape:"htmlall"}</option>
						{/foreach}
					</select>
				</li>
				<li class="postalcode">
					<label for="city">##JOBCITY_POSTCODE## :</label>
					<input type="text" name="locationinfos[city_postcode]">
				</li>
			</ul>
		</fieldset>
		<div class="button">
			<input type="submit" value="{if isset($myLocation)}##LOCATIONSAVE##{else}##LOCATIONCREATE##{/if}" />
		</div>
	</form>
</div>