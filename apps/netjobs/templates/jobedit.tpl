<h1>##NETJOBS##</h1>
{if isset($myJob) && $myJob != FALSE}
<h2>{if isset($myJob)}##JOBMODIFY##{else}##JOBADD##{/if}</h2>
<div class="netjobs">
	<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	<form action="{kurl action="jobsave"}" method="post" class="jobedit">
	{include file="formmessage.tpl"}
		{if isset($myJob)}
			<input type="hidden" name="jobinfos[id]" value="{$myJob->getInfo("id")}">
		{/if}
		<ul>
			<li class="title">
				<label for="title">##JOBTITLE## :</label>
				<input type="text" id="title" name="jobinfos[title]" value="{if isset($myJob)}{$myJob->getInfo("title")|escape:"html"}{/if}" />
			</li>
			<li class="description">
				<label for="description">##NEWS_DESCRIPTION## : </label>
				<textarea name="jobinfos[description]" id="description" rows="10" cols="60" />{if isset($myJob)}{$myJob->getInfo("description")|escape:"html"}{/if}</textarea>
			</li>
			<li class="type">
				<label for="type">##JOBTYPE## :</label>
				<input type="text" id="type" name="jobinfos[type]" value="{if isset($myJob)}{$myJob->getInfo("type")|escape:"html"}{/if}" />
			</li>
			<li class="salary">
				<label for="salary">##JOBSALARY## :</label>
				<input type="text" id="salary" name="jobinfos[salary]" value="{if isset($myJob)}{$myJob->getInfo("salary")|escape:"html"}{/if}" />
			</li>
			<li class="company">
				<label for="company">##JOBCOMPANY## :</label>
				<input type="text" id="company" name="jobinfos[company_id]" value="{if isset($myJob)}{$myJob->getInfo("company_id")|escape:"html"}{/if}" />
			</li>			
			<div class="button">
				<input type="submit" value="{if isset($myJob)}##JOBSAVE##{else}##JOBCREATE##{/if}" />
			</div>
		</ul>
	</form>
</div>
{else}
##JOBNOTFOUND##
{/if}