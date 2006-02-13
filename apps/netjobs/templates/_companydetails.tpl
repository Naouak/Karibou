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