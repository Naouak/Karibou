<h1>##NETJOBS_TITLE##</h1>
<h3>##NETJOBS_JOBLIST##</h3>

<a href="{kurl page="jobedit"}">##JOBADD##</a>

<div class="netjobs">
	<ul class="joblist">
{foreach from=$myJobs item="myJob"}
		<li>
			<span class="company">{$myJob->getCompanyInfo("name")|truncate:30:"..."}</span>
			<span class="title">{$myJob->getInfo("title")}</span>
			<span class="type">{$myJob->getInfo("description")|truncate:30:"..."}&nbsp;</span>
			<span class="datetime">{$myJob->getInfo("datetime")}&nbsp;</span>
			<span class="editlink"><a href="{kurl page="jobedit" jobid=$myJob->getInfo("id")}">##EDIT##</a></span>
		</li>
{/foreach}
	</ul>

</div>