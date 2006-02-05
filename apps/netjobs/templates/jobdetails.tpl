<h1>##NETJOBS_TITLE##</h1>
{if isset($myJob) && $myJob != FALSE}
<h3>##NETJOBS_JOBTITLE## : {$myJob->getInfo("title")}</h3>

<div class="netjobs">
	<ul class="jobdetails">
	
		<li class="type">
			<label for="type">##JOBTYPE## :</label>
			<span name="type">{translate key=$myJob->getInfo("type")}</span>
		</li>
		
		<li class="company">
			<label for="company">##JOBCOMPANY## :</label>
			<span name="company">
			{if ($myJob->getCompanyInfo("id") !== FALSE) }
				<a href="{kurl page="companydetails" companyid=$myJob->getCompanyInfo("id")}">{$myJob->getCompanyInfo("name")}</a>&nbsp;
			{else}
			##COMPANYUNKNOWN##
			{/if}
			</span>
		</li>
		
		{if isset($myContact) && $myContact|@count>0}
		<li class="contact">
			<label for="contact">##JOB_CONTACT## :</label>
			<span name="contact">
			{if ($myContact.profile.id !== FALSE) }
				<a href="{kurl page="contactdetails" contactid=$myContact.profile.id}">{$myContact.profile.firstname} {$myContact.profile.lastname}</a>&nbsp;
			{else}
			##COMPANYUNKNOWN##
			{/if}
			</span>
		</li>
		{/if}

		<li class="datetime">
			<label for="datetime">##JOBPOSTDATE## : </label>
			<span name="datetime">{$myJob->getInfo("datetime")}&nbsp;</span>
		</li>
		
		<li class="description">
			<label for="type">##JOBDESCRIPTION## : </label>
			<span name="type" class="textzone">{$myJob->getInfo("description")}&nbsp;</span>
		</li>
		
		<li class="profile">
			<label for="type">##JOBPROFILE## : </label>
			<span name="type" class="textzone">{$myJob->getInfo("profile")}&nbsp;</span>
		</li>
		
		<li class="salary">
			<label for="salary">##JOBSALARY## : </label>
			<span name="salary">{$myJob->getInfo("salary")}&nbsp;</span>
		</li>
		
		<li class="education">
			<label for="education">##JOBEDUCATIONLEVEL## :</label>
			<span name="education">
				{if $myJob->getInfo("education") !== FALSE && ($myJob->getInfo("education") != "") }
					{translate key=$myJob->getInfo("education")}
				{else}
					##jobel_none##
				{/if}
				&nbsp;
			</span>
		</li>
		
		<li class="experience_required">
			<label for="type">##JOBEXPERIENCEREQUIRED## :</label>
				{if ($myJob->getInfo("experience_required")) != ""}
			<span name="type" class="textzone">
					{$myJob->getInfo("experience_required")}
				{else}
			<span name="type">
					##NONE##
				{/if}
				&nbsp;
			</span>
		</li>
		
		<li class="creator">
			<label for="creator">##JOBCREATOR## :</label>
			<span id="creator">
				<a href="{kurl app="annuaire" username=$myJob->creator->getLogin()}">{$myJob->creator->getUserLink()}</a>&nbsp;
			</span>
		</li>

	</ul>	
	{if ($myJob->canUpdate())}
	<div class="toolbox">
		<a href="{kurl page="jobedit" jobid=$myJob->getInfo("id")}" class="edit"><span>##JOBEDIT##</span></a>
		{if ($myJob->canWrite())}
		<form method="post" action="{kurl page="jobsave"}" id="deletejob" name="delete">
			<input type="hidden" name="jobiddelete" value="{$myJob->getInfo("id")}">
			<a href="#" onclick="if(confirm('##JOBDELETE_ASKIFSURE##')){ldelim}document.getElementById('deletejob').submit(){rdelim}else{ldelim}return false;{rdelim};" class="delete"><span>##JOBDELETE##</span></a>
		</form>
		{/if}
	</div>
	{/if}

</div>
{else}
##JOBNOTFOUND##
{/if}