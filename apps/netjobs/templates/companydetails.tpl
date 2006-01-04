<h1>##NETJOBS_TITLE##</h1>
{if isset($myCompany) && $myCompany != FALSE}
<h3>##NETJOBS_COMPANYTITLE##</h3>

<div class="netjobs">
	<ul class="companydetails">

		<li class="name">
			<label for="name">##COMPANYNAME## :</label>
			<span name="name">{$myCompany->getInfo("name")}</span>
			</a>
		</li>
		
		<li class="type">
			<label for="type">##COMPANYTYPE## :</label>
			<span name="type">{translate key=$myCompany->getInfo("type")}</span>
			</a>
		</li>

		<li class="datetime">
			<label for="datetime">##COMPANYPOSTDATE## : </label>
			<span name="datetime">{$myCompany->getInfo("datetime")}&nbsp;</span>
			</a>
		</li>

		<li class="description">
			<label for="type">##COMPANYDESCRIPTION## : </label>
			<span name="type">{$myCompany->getInfo("description")}&nbsp;</span>
			</a>
		</li>

		<li class="joboffers">
			<label for="joboffers">##COMPANYJOBOFFERS## :</label>
			<span id="joboffers">{$myCompany->getInfo("joboffers")}&nbsp;</span>
		</li>

		<li class="creator">
			<label for="creator">##COMPANYCREATOR## :</label>
			<span id="creator">
				<a href="{kurl app="annuaire" username=$myCompany->creator->getLogin()}">{$myCompany->creator->getUserLink()}</a>&nbsp;
			</span>
		</li>

	</ul>
	
	{if ($myCompany->canUpdate())}
	<div class="toolbox">
		<span class="editlink"><a href="{kurl page="companyedit" companyid=$myCompany->getInfo("id")}">##COMPANYEDIT##</a></span>
		{if ($myCompany->canWrite())}
		<form method="post" action="{kurl page="companysave"}" id="deletecompany" name="delete">
			<input type="hidden" name="companyiddelete" value="{$myCompany->getInfo("id")}">
			<a href="#" onclick="if(confirm('##COMPANYDELETE_ASKIFSURE##')){ldelim}document.deletejob.submit(){rdelim}else{ldelim}return false;{rdelim};">##COMPANYDELETE##</a>
		</form>
		{/if}
	</div>
	{/if}

</div>
{else}
##COMPANYNOTFOUND##
{/if}