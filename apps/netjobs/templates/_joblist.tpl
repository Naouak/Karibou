		<ul class="joblist">
	{foreach from=$myJobs item="myJob"}
			<li>
				<span class="company">{$myJob->getCompanyInfo("name")|truncate:30:"..."}</span>
				<span class="title"><a href="{kurl page="jobdetails" jobid=$myJob->getInfo("id")}">{$myJob->getInfo("title")}</a></span>
				<span class="type">{$myJob->getInfo("description")|truncate:30:"..."}&nbsp;</span>
				<span class="datetime">{$myJob->getInfo("datetime")}&nbsp;</span>
			</li>
	{/foreach}
		</ul>