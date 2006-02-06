<h1>##NETJOBS_TITLE##</h1>
{if isset($myCompany) && $myCompany != FALSE}
<h3>##NETJOBS_COMPANYTITLE## &quot;{$myCompany->getInfo("name")}&quot;</h3>

<div class="netjobs">
	<div  class="companydetails">
		<ul>
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
				<span name="type" class="textzone">{$myCompany->getInfo("description")}&nbsp;</span>
				</a>
			</li>
	
			<li class="joboffers">
				<label for="joboffers">##COMPANYJOBOFFERS## :</label>
				<span id="joboffers">
					{if ($myCompany->getInfo("joboffers") > 0)}
					<a href="{kurl page="joblist" pagenum=0 maxjobs=0 companyid=$myCompany->getInfo("id")}" title="##COMPANY_VIEWALLJOBS##">{$myCompany->getInfo("joboffers")}</a>
					{else}##NONE##{/if}&nbsp;</span>
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
			<a href="{kurl page="companyedit" companyid=$myCompany->getInfo("id")}" class="edit"><span>##COMPANYEDIT##</span></a>
			{if ($myCompany->canWrite())}
			<form method="post" action="{kurl page="companysave"}" id="deletecompany" name="delete">
				<input type="hidden" name="companyiddelete" value="{$myCompany->getInfo("id")}">
				<a href="#" onclick="if(confirm('##COMPANYDELETE_ASKIFSURE##')){ldelim}document.getElementById('deletecompany').submit(){rdelim}else{ldelim}return false;{rdelim};" class="delete"><span>##COMPANYDELETE##</span></a>
			</form>
			{/if}
		</div>
		{/if}
	</div>
</div>
{else}
##COMPANYNOTFOUND##
{/if}