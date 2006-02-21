<h1>##NETJOBS_TITLE##</h1>
{if (isset($myJob))}
<h2>##JOB_ADDING## &quot;{$myJob->getInfo("title")}&quot;</h2>
<h3>##COMPANY_ADD##</h3>
{else}
<h2>{if isset($myCompany)}##COMPANY_MODIFY##{else}##COMPANY_ADD##{/if}</h2>
{/if}
<div class="netjobs">
	<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	<form action="{kurl action="companysave"}" method="post" class="jobedit">
		{if isset($myCompany)}
			<input type="hidden" name="companyinfos[id]" value="{$myCompany->getInfo("id")}">
		{/if}
		{if (isset($myJob))}
			<input type="hidden" name="jobid" value="{$myJob->getInfo("id")}">
		{/if}

		<fieldset>
			<legend>{if isset($myCompany)}##COMPANY_MODIFY##{else}##COMPANY_ADD##{/if}</legend>
			<ul>
				<li class="name">
					<label for="name">##COMPANYNAME## :</label>
					<input type="text" id="name" name="companyinfos[name]" value="{if isset($myCompany)}{$myCompany->getInfo("name")|escape:"html"}{/if}" />
				</li>
				<li class="description">
					<label for="description">##COMPANYDESCRIPTION## : </label>
					<textarea id="description" name="companyinfos[description]" rows="5" cols="60" />{if isset($myCompany)}{$myCompany->getInfo("description")|escape:"html"}{/if}</textarea>
				</li>
				<li class="type">
					<label for="type">##COMPANYTYPE## :</label>
					<select id="type" name="companyinfos[type]">
						<option value="compt_company"{if isset($myCompany) && $myCompany->getInfo("type") == "compt_company"} SELECTED{/if}>##compt_company##</option>
						<option value="compt_recruitingagency"{if isset($myCompany) && $myCompany->getInfo("type") == "compt_recruitingagency"} SELECTED{/if}>##compt_recruitingagency##</option>
						<option value="compt_interimagency"{if isset($myCompany) && $myCompany->getInfo("type") == "compt_interimagency"} SELECTED{/if}>##compt_interimagency##</option>
						<option value="compt_service"{if isset($myCompany) && $myCompany->getInfo("type") == "compt_service"} SELECTED{/if}>##compt_service##</option>
						<option value="compt_organisation"{if isset($myCompany) && $myCompany->getInfo("type") == "compt_organisation"} SELECTED{/if}>##compt_organisation##</option>
						<option value="compt_other"{if isset($myCompany) && $myCompany->getInfo("type") == "jobt_interim"} SELECTED{/if}>##compt_other##</option>
					</select>
				</li>
				<li class="sector">
					<label for="type">##COMPANYACTIVITY## :</label>
					<select id="type" name="companyinfos[activity]">
						<option value="compa_construction"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_construction"} SELECTED{/if}>##compa_construction##</option>
						<option value="compa_realestate"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_realestate"} SELECTED{/if}>##compa_realestate##</option>
						<option value="compa_bankinsurance"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_bankinsurance"} SELECTED{/if}>##compa_bankinsurance##</option>
						<option value="compa_distribution"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_distribution"} SELECTED{/if}>##compa_distribution##</option>
						<option value="compa_infotelecom"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_infotelecom"} SELECTED{/if}>##compa_infotelecom##</option>
						<option value="compa_commonpublic"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_commonpublic"} SELECTED{/if}>##compa_commonpublic##</option>
						<option value="compa_services"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_services"} SELECTED{/if}>##compa_services##</option>
						<option value="compa_industry"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_industry"} SELECTED{/if}>##compa_industry##</option>
						<option value="compa_other"{if isset($myCompany) && $myCompany->getInfo("sector") == "compa_other"} SELECTED{/if}>##compa_other##</option>					</select>
				</li>
			</ul>
		</fieldset>

		<div class="button">
			<input type="submit" value="{if isset($myCompany)}##COMPANYSAVE##{else}##COMPANYCREATE##{/if}" />
		</div>
	</form>
</div>