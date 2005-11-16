<div class="netcv">
	{include file="applinks.tpl"}
	<h1>##CONTENT_CHECK##</h1>
	<h4>##MINIMA_REQUIRED##</h4>
	<p>
		##MINIMA_REQUIRED_DESCRIPTION##
		<ul>
			<li>{$config.minimum.sections} ##SECTION##</li>
			<li>{$config.minimum.elements} ##ELEMENT##s ({$config.minimum.elements/$config.minimum.sections} ##ELEMENTS_BY_SECTION##)</li>
			<li>{$config.minimum.personalinfos} ##PERSONALINFO##</li>
		</ul>
	</p>
	<h4>##PERSONALINFO_STATE##</h4>
	<p>
	{if ($config.minimum.personalinfos <= ($myNetCVUser->countPersonalInfo() - $config.personalinfos.system))}
		<span class="ok"><strong>##ENOUGH_PERSONALINFO##</strong></span>
	{else}
		<span class="ko"><strong>##NOT_ENOUGH_PERSONALINFO##</strong></span>
		<br />
		<a href="{kurl page="personalinfo"}">##CLICK_HERE_TO_MODIFY_YOUR_PERSOINFO##</a>
	{/if}
	</p>
	<h4>##CV_STATE##</h4>
	<p>
	{assign var="myNetCVGroupsObjects" value=$myNetCVGroupList->returnGroupsObjects()}
	{if ($myNetCVGroupsObjects|@count > 0) }
		<ul>
	    {section name=i loop=$myNetCVGroupsObjects step=1}
	    	<li>
	    	{assign var="aGroup" value=$myNetCVGroupsObjects[i]}
	    	{assign var="aGroupId" value=$aGroup->getInfo("id")}
					<strong>{$aGroup->getInfo("name")}</strong>
					{assign var="aCVList" value=$aGroup->returnCVList()}
					<ul>
					{section name=j loop=$aCVList step=1}
						{assign var="aCV" value=$aCVList[j]}
						{assign var="aCVContent" value=$aCV->getContent()}
						{assign var="nbSections" value=$aCV->countSections()}
						{assign var="nbElements" value=$aCV->countElements()}
						<li>
								<a href="{kurl page="cvsectionlist" cvid=$aCV->getInfo("id") gid=$aGroupId}">{$aCV->getInfo("lang")}</a>
								{if ($nbSections > 0)}
									{$nbSections} ##SECTION##{if ($nbSections>1)}s{/if} ##AND##
									{if ($nbElements)} {$nbElements} ##ELEMENT##{if ($nbElements>1)}s{/if}{/if}.
									{if ($nbElements >= $config.minimum.elements) && ($nbSections >= $config.minimum.sections)}
										<span class="ok"><strong>##ENOUGH_CONTENT##</strong></span>
									{else}
										<span class="ko"><strong>##NOT_ENOUGH_CONTENT##</strong></span>
									{/if}
								{else}<span class="ko"><strong>##NO_SECTION##</strong></span>{/if} 
						</li>
	    			{/section}
	    			</ul>
			</li>
	    {/section}
		</ul>
	{else}
		##NO_CV##
	{/if}
	</p>
	<h4>##GO_CREATE##</h4>
	<p>
		<a href="{kurl page=""}">##BACK_HOME##</a>
	</p>
</div>