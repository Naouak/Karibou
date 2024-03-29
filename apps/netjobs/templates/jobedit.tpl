<h1>##NETJOBS_TITLE##</h1>
<h2>{if isset($myJob)}##JOB_MODIFY##{else}##JOBADD##{/if}</h2>
<div class="netjobs">
	<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	<form action="{kurl action="jobsave"}" method="post" class="jobedit">
	{*include file="formmessage.tpl"*}
		{if isset($myJob)}
			<input type="hidden" name="jobinfos[id]" value="{$myJob->getInfo("id")}">
		{/if}
		<fieldset>
			<legend>##JOB_INFOS##</legend>
			<ul>
				<li class="title">
					<label for="title">##JOBTITLE## :</label>
					<input type="text" id="title" name="jobinfos[title]" size="55" value="{if isset($myJob)}{$myJob->getInfo("title")|escape:"html"}{/if}" />
				</li>
				<li class="description">
					<label for="description">##JOBDESCRIPTION## : </label>
					<textarea id="description" name="jobinfos[description]" rows="5" cols="60">{if isset($myJob)}{$myJob->getInfo("description")|escape:"html"}{/if}</textarea>
				</li>
				<li class="profile">
					<label for="profile">##JOBPROFILE## : </label>
					<textarea id="profile" name="jobinfos[profile]" rows="5" cols="60">{if isset($myJob)}{$myJob->getInfo("profile")|escape:"html"}{/if}</textarea>
				</li>
				<li class="experiencerequired">
					<label for="experiencerequired">##JOBEXPERIENCEREQUIRED## :</label>
					<textarea id="experiencerequired" name="jobinfos[experience_required]" rows="3" cols="60">{if isset($myJob)}{$myJob->getInfo("experience_required")|escape:"html"}{/if}</textarea>
					<span class="note">##JOBEXPERIENCEREQUIRED_LETBLANKFORNONE##</span>
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
						<option value="jobel_bac+5"{if isset($myJob) && $myJob->getInfo("education") == "jobel_bac+5"} SELECTED{/if}>##jobel_bac+5##</option>
						<option value="jobel_engineer"{if isset($myJob) && $myJob->getInfo("education") == "jobel_engineer"} SELECTED{/if}>##jobel_engineer##</option>
						<option value="jobel_trader"{if isset($myJob) && $myJob->getInfo("education") == "jobel_trader"} SELECTED{/if}>##jobel_trader##</option>
					</select>
				</li>
				<li class="role">
					<label for="role">##JOBROLE## :</label>
					<select id="role" name="jobinfos[role]">
						<option value="jobr_administration"{if isset($myJob) && $myJob->getInfo("role") == "jobr_administration"} SELECTED{/if}>##jobr_administration##</option>
						<option value="jobr_finance"{if isset($myJob) && $myJob->getInfo("role") == "jobr_finance"} SELECTED{/if}>##jobr_finance##</option>
						<option value="jobr_audit"{if isset($myJob) && $myJob->getInfo("role") == "jobr_audit"} SELECTED{/if}>##jobr_audit##</option>
						<option value="jobr_consulting"{if isset($myJob) && $myJob->getInfo("role") == "jobr_consulting"} SELECTED{/if}>##jobr_consulting##</option>
						<option value="jobr_law"{if isset($myJob) && $myJob->getInfo("role") == "jobr_law"} SELECTED{/if}>##jobr_law##</option>
						<option value="jobr_hrtraining"{if isset($myJob) && $myJob->getInfo("role") == "jobr_hrtraining"} SELECTED{/if}>##jobr_hrtraining##</option>
						<option value="jobr_researchdevelopment"{if isset($myJob) && $myJob->getInfo("role") == "jobr_researchdevelopment"} SELECTED{/if}>##jobr_researchdevelopment##</option>
						<option value="jobr_healthsocial"{if isset($myJob) && $myJob->getInfo("role") == "jobr_healthsocial"} SELECTED{/if}>##jobr_healthsocial##</option>
						<option value="jobr_logisticbuyer"{if isset($myJob) && $myJob->getInfo("role") == "jobr_logisticbuyer"} SELECTED{/if}>##jobr_logisticbuyer##</option>
						<option value="jobr_productionsecurity"{if isset($myJob) && $myJob->getInfo("role") == "jobr_productionsecurity"} SELECTED{/if}>##jobr_productionsecurity##</option>
						<option value="jobr_computer"{if isset($myJob) && $myJob->getInfo("role") == "jobr_computer"} SELECTED{/if}>##jobr_computer##</option>
						<option value="jobr_telecomnetworks"{if isset($myJob) && $myJob->getInfo("role") == "jobr_telecomnetworks"} SELECTED{/if}>##jobr_telecomnetworks##</option>
						<option value="jobr_multimediainternet"{if isset($myJob) && $myJob->getInfo("role") == "jobr_multimediainternet"} SELECTED{/if}>##jobr_multimediainternet##</option>
						<option value="jobr_sales"{if isset($myJob) && $myJob->getInfo("role") == "jobr_sales"} SELECTED{/if}>##jobr_sales##</option>
						<option value="jobr_export"{if isset($myJob) && $myJob->getInfo("role") == "jobr_export"} SELECTED{/if}>##jobr_export##</option>
						<option value="jobr_marketing"{if isset($myJob) && $myJob->getInfo("role") == "jobr_marketing"} SELECTED{/if}>##jobr_marketing##</option>
						<option value="jobr_communication"{if isset($myJob) && $myJob->getInfo("role") == "jobr_communication"} SELECTED{/if}>##jobr_communication##</option>
						<option value="jobr_officeleadership"{if isset($myJob) && $myJob->getInfo("role") == "jobr_officeleadership"} SELECTED{/if}>##jobr_officeleadership##</option>
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
					<input type="text" id="salary" name="jobinfos[salary]" size="4" maxlength="4" value="{if isset($myJob)}{$myJob->getInfo("salary")|escape:"html"}{/if}" />
					K€ (##JOB_KEURO##)
				</li>
			</ul>
		</fieldset>

		<fieldset>
			<legend>##JOB_COMPANY##</legend>
			<ul>
				<li class="company">
					<label for="company">##JOBCOMPANY## :</label>
					{if isset($myJob)}{assign var=jobcompanyid value=$myJob->getInfo("company_id")}{/if}
					<select name="jobinfos[company_id]">
						<option value="new">##JOBCOMPANY_NEW##</option>
						{foreach from=$allCompanies item="company"}
							<option value="{$company->getInfo("id")}"{if $jobcompanyid == $company->getInfo("id")} SELECTED{/if}>{$company->getInfo("name")}</option>
						{/foreach}
					</select>
					{khint langmessage="JOBCOMPANY_NEW_NOTE" type="info"}
				</li>
			</ul>
		</fieldset>

		<div class="button">
			<input type="submit" value="{if isset($myJob)}##JOBSAVE##{else}##JOBCREATE##{/if}" />
		</div>
	</form>
</div>