<h1>##NETJOBS_TITLE##</h1>
{if isset($myJob) && $myJob != FALSE}
<h3>##NETJOBS_JOBTITLE## : {$myJob->getInfo("title")}</h3>

<div class="netjobs">
<a href="{kurl page="jobedit" jobid=$myJob->getInfo("id")}">Edit</a>
	<ul class="jobdetails">
	
		<li class="type">
			<label for="type">##JOBTYPE## :</label>
			<span name="type">{$myJob->getInfo("type")}</span>
			</a>
		</li>
		
		<li class="company">
			<label for="company">##JOBCOMPANY## :</label>
			<span name="company">{$myJob->getCompanyInfo("name")}</span>
			</a>
		</li>

		<li class="datetime">
			<label for="datetime">##JOBPOSTDATE## : </label>
			<span name="datetime">{$myJob->getInfo("datetime")}&nbsp;</span>
			</a>
		</li>
		
		<li class="description">
			<label for="type">##JOBDESCRIPTION## : </label>
			<span name="type">{$myJob->getInfo("description")}&nbsp;</span>
			</a>
		</li>
		
		<li class="salary">
			<label for="salary">##JOBSALARY## : </label>
			<span name="salary">{$myJob->getInfo("salary")}&nbsp;</span>
			</a>
		</li>
		
		<li class="experience_required">
			<label for="type">##JOBEXPERIENCEREQUIRED## :</label>
			<span name="type">
				{if ($myJob->getInfo("experience_required")) > 0}
					{$myJob->getInfo("experience_required")}
				{else}
					##NONE##
				{/if}
				&nbsp;
			</span>
			</a>
		</li>
		
		<li class="creator">
			<label for="creator">##JOBCREATOR## :</label>
			<span id="creator">
				<a href="{kurl app="annuaire" username=$myJob->creator->getLogin()}">{$myJob->creator->getUserLink()}</a>&nbsp;
			</span>
		</li>

	</ul>

</div>
{else}
##JOBNOTFOUND##
{/if}