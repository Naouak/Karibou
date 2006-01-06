<h1>##NETJOBS##</h1>
<h2>{if isset($myJob)}##JOBMODIFY##{else}##JOBADD##{/if}</h2>
<div class="netjobs">
	<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	<form action="{kurl action="jobsave"}" method="post" class="jobedit">
	{*include file="formmessage.tpl"*}
		{if isset($myJob)}
			<input type="hidden" name="jobinfos[id]" value="{$myJob->getInfo("id")}">
		{/if}
		<ul>
			<li class="title">
				<label for="title">##JOBTITLE## :</label>
				<input type="text" id="title" name="jobinfos[title]" value="{if isset($myJob)}{$myJob->getInfo("title")|escape:"html"}{/if}" />
			</li>
			<li class="description">
				<label for="description">##JOBDESCRIPTION## : </label>
				<textarea id="description" name="jobinfos[description]" rows="5" cols="60" />{if isset($myJob)}{$myJob->getInfo("description")|escape:"html"}{/if}</textarea>
			</li>
			<li class="profile">
				<label for="profile">##JOBPROFILE## : </label>
				<textarea id="profile" name="jobinfos[profile]" rows="5" cols="60" />{if isset($myJob)}{$myJob->getInfo("profile")|escape:"html"}{/if}</textarea>
			</li>
			<li class="experiencerequired">
				<label for="experiencerequired">##JOBEXPERIENCEREQUIRED## :</label>
				<textarea id="experiencerequired" name="jobinfos[experience_required]" rows="3" cols="60" />{if isset($myJob)}{$myJob->getInfo("experience_required")|escape:"html"}{/if}</textarea>
				<span class="info">##JOBEXPERIENCEREQUIRED_LETBLANKFORNONE##</span>
			</li>
			<li class="type">
				<label for="type">##JOBTYPE## :</label>
				<select id="type" name="jobinfos[type]">
					<option value="jobt_internship"{if isset($myJob) && $myJob->getInfo("type") == "jobt_internship"} SELECTED{/if}>##jobt_internship##</option>
					<option value="jobt_permanent"{if isset($myJob) && $myJob->getInfo("type") == "jobt_permanent"} SELECTED{/if}>##jobt_permanent##</option>
					<option value="jobt_tempcontract"{if isset($myJob) && $myJob->getInfo("type") == "jobt_tempcontract"} SELECTED{/if}>##jobt_tempcontract##</option>
					<option value="jobt_interim"{if isset($myJob) && $myJob->getInfo("type") == "jobt_interim"} SELECTED{/if}>##jobt_interim##</option>
				</select>
			</li>
			<li class="education">
				<label for="education">##JOBEDUCATIONLEVEL## :</label>
				<select id="education" name="jobinfos[education]">
					<option value="jobel_none"{if isset($myJob) && $myJob->getInfo("education") == "jobel_none"} SELECTED{/if}>##jobel_none##</option>
					<option value="jobel_bac+2"{if isset($myJob) && $myJob->getInfo("education") == "jobel_bac+2"} SELECTED{/if}>##jobel_bac+2##</option>
					<option value="jobel_bac+3"{if isset($myJob) && $myJob->getInfo("education") == "jobel_bac+3"} SELECTED{/if}>##jobel_bac+3##</option>
					<option value="jobel_bac+4"{if isset($myJob) && $myJob->getInfo("education") == "jobel_bac+4"} SELECTED{/if}>##jobel_bac+4##</option>
					<option value="jobel_bac+5"{if isset($myJob) && $myJob->getInfo("education") == "jobel_bac+5"} SELECTED{/if}>##JOBEL_bac+5##</option>
					<option value="jobel_engineer"{if isset($myJob) && $myJob->getInfo("education") == "jobel_engineer"} SELECTED{/if}>##jobel_engineer##</option>
					<option value="jobel_trader"{if isset($myJob) && $myJob->getInfo("education") == "jobel_trader"} SELECTED{/if}>##jobel_trader##</option>
				</select>
			</li>
			{*
			<li class="jobstartdate">
				<label for="jobstartdate">##JOBSTARTDATE## :</label>
					{html_select_date day_extra='class="input_xs"' month_extra='class="input_m"' year_extra='class="input_s"' prefix="jobstartdate" start_year="-1" end_year="+6" field_order="DMY"}
			</li>
			<li class="jobenddate">
				<label for="jobenddate">##JOBENDDATE## :</label>
					{html_select_date day_extra='class="input_xs"' month_extra='class="input_m"' year_extra='class="input_s"' prefix="jobenddate" start_year="-1" end_year="+6" field_order="DMY"}
			</li>
			*}
			<li class="salary">
				<label for="salary">##JOBSALARY## :</label>
				<input type="text" id="salary" name="jobinfos[salary]" value="{if isset($myJob)}{$myJob->getInfo("salary")|escape:"html"}{/if}" />
			</li>
			<li class="company">
				<label for="company">##JOBCOMPANY## :</label>
				{if isset($myJob)}{assign var=jobcompanyid value=$myJob->getInfo("company_id")}{/if}
				<select name="jobinfos[company_id]">
					<option DISABLED>##JOBCHOOSECOMPANY##</option>
					{foreach from=$allCompanies item="company"}
						<option value="{$company->getInfo("id")}"{if $jobcompanyid == $company->getInfo("id")} SELECTED{/if}>{$company->getInfo("name")}</option>
					{/foreach}
				</select>
				##OR##
				<input type="text" id="company" name="newcompany" value="##JOBADDCOMPANY##" />
			</li>
			<li class="country">
				<label for="country">##JOBCOUNTRY## :</label>
				{if isset($myJob)}{assign var=jobcountryid value=$myJob->getInfo("country_id")}{else}{*France = Default*}{assign var=jobcountryid value="99189"}{/if}
				<select name="jobinfos[country_id]">
					<option DISABLED>##JOBCHOOSECOUNTRY##</option>
					{foreach from=$countries item="country"}
						<option value="{$country.id}"{if $jobcountryid == $country.id} SELECTED{/if}>{$country.name|escape:"htmlall"}</option>
					{/foreach}
				</select>
			</li>
			<li class="city">
				<label for="city">##JOBCITY## :</label>
				{if isset($myJob)}
					{assign var=jobcityid value=$myJob->getCity()}
				{/if}
				<select name="jobinfos[country_id]">
					<option DISABLED>##JOBCHOOSECOUNTRY##</option>
					{foreach from=$countries item="country"}
						<option value="{$country.id}"{if $jobcountryid == $country.id} SELECTED{/if}>{$country.name|escape:"htmlall"}</option>
					{/foreach}
				</select>
			</li>
			<li class="contact">
				<label for="company">##JOBCONTACT## :</label>
				{if isset($myJob)}{assign var=jobcontactid value=$myJob->getInfo("contact_id")}{/if}
				<select name="jobinfos[company_id]">
					<option DISABLED>##JOBCHOOSECOMPANY##</option>
					{foreach from=$allCompanies item="company"}
						<option value="{$company->getInfo("id")}"{if $jobcompanyid == $company->getInfo("id")} SELECTED{/if}>{$company->getInfo("name")}</option>
					{/foreach}
				</select>
				##OR##
				<input type="text" id="company" name="newcompany" value="##JOBADDCOMPANY##" />
			</li>		
		</ul>
		<div class="button">
			<input type="submit" value="{if isset($myJob)}##JOBSAVE##{else}##JOBCREATE##{/if}" />
		</div>
	</form>
</div>