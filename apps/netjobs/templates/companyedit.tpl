<h1>##NETJOBS##</h1>
<h2>{if isset($myCompany)}##COMPANYMODIFY##{else}##COMPANYADD##{/if}</h2>
<div class="netjobs">
	<a href="{kurl app="wiki" page="help"}" onclick="javascript:popup(this.href, 'wiki_help', '800', '900', '200', '200');return false;" >##TITLE_WIKI_SYNTAX##</a>
	<form action="{kurl action="companysave"}" method="post" class="jobedit">
	{*include file="formmessage.tpl"*}
		{if isset($myCompany)}
			<input type="hidden" name="companyinfos[id]" value="{$myCompany->getInfo("id")}">
		{/if}
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
		</ul>
		<div class="button">
			<input type="submit" value="{if isset($myCompany)}##COMPANYSAVE##{else}##COMPANYCREATE##{/if}" />
		</div>
	</form>
</div>