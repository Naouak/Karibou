<h1>##SURVEY##</h1>
<h2>{$mySurvey->getInfo("name")}</h2>

{if $mySurvey->getInfo("description") != ""}
<div class="helper">
	{$mySurvey->getInfo("description")}
</div>
{/if}

<table border="1">
	{foreach name="answers" from=$answers item="answer" key="userid"}
		{if $smarty.foreach.answers.first}
	<tr>
		<td>
				##KS_DETAILS##
		</td>
		<td>
				##KS_USER##
		</td>
			{foreach from=$answer key=key item=item}
		<td>
				{assign var=question value=$mySurvey->getQuestionById($key)}
				{$question->getInfo("name")}
		</td>
			{/foreach}
	</tr>
		{/if}
	<tr>
		<td>
				<a href="{kurl page="displayuseranswers" surveyid=$mySurvey->getInfo("id") userid=$userid}"><span>##KS_DETAILS##</span></a>
		</td>
		<td style="witdh: 10px; overflow: hidden;">
			{userlink user=$answer[$userid]->creator showpicture=true}
		</td>
		{foreach from=$answer key=key item=item}
		<td>
			{$item->getInfo("value")}
		</td>
		{/foreach}
	</tr>
	{/foreach}
</table>