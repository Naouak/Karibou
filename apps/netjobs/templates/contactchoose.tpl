<h1>##NETJOBS_TITLE##</h1>
<h2>##JOB_CHOOSECONTACT##</h2>
<div class="netjobs">
	<form action="{kurl page="contactchoicesave"}" method="POST">
		<fieldset>
			<legend>##JOB_CONTACT##</legend>
			<ul>
				<li class="contact">
					<label for="contactid">##JOB_CONTACT## :</label>
					{if isset($myJob)}{assign var=jobcontactid value=$myJob->getInfo("contactid")}{/if}
					<select name="contactid">
						<option DISABLED>##JOB_CHOOSECONTACT##</option>
						<option value="new">##JOB_CREATECONTACT##</option>
						{foreach from=$allContactsInCompany item="contact"}
							{assign var="profile" value=$contact->getProfile()}
							<option value="{$profile.id}"{if $jobcontactid == $profile.id} SELECTED{/if}>{$profile.firstname} {$profile.lastname}</option>
						{/foreach}
					</select>
				</li>		
			</ul>
		</fieldset>
		<input type="hidden" name="jobid" value="{$jobid}">
		<input type="hidden" name="companyid" value="{$companyid}">
		<div class="button">
			<input type="submit">
		</div>
	</form>
</div>