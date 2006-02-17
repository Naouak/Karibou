<h1>##SURVEY##</h1>
{if ($admin)}
	<h2>{$mySurvey->getInfo("name")}</h2>
	
	{if $mySurvey->getInfo("description") != ""}
	<div class="helper">
		{$mySurvey->getInfo("description")}
	</div>
	{/if}
	
	<table border="1">
		{foreach name="verionsanswers" from=$verionsanswers item="versionsanswer" key="userid"}
			{if $smarty.foreach.verionsanswers.first}
		<tr>
			<th>
					##KS_DETAILS##
			</th>
			<th>
					##KS_VERSIONID##
			</th>
			<th>
					##KS_USER##
			</th>
	
				{foreach from=$mySurvey->getAllQuestions() key=key item=question}
			<th>
					{$question->getInfo("name")}
			</th>
				{/foreach}
		</tr>
			{/if}
			{foreach name="versionanswer" from=$versionsanswer item="versionanswer" key="versionid"}
			<tr>
				<td>
						<a href="{kurl page="displayuseranswers" surveyid=$mySurvey->getInfo("id") userid=$userid}"><span>##KS_DETAILS##</span></a>
				</td>
				<td>
						{$versionid}&nbsp;
				</td>
				{foreach name="versionanswer" from=$versionanswer key=key item=item}
					{if $smarty.foreach.versionanswer.first}
					<td style="witdh: 10px; overflow: hidden;">
						{userlink user=$item->creator showpicture=true}&nbsp;
					</td>
					{/if}
				<td>
					{$item->getInfo("value")}&nbsp;
				</td>
				{/foreach}
			</tr>
			{/foreach}
		{/foreach}
	</table>
{else}
	##KS_NOPERMISSION##
{/if}