<h1>##SURVEY##</h1>
<h2>{$mySurvey->getInfo("name")}</h2>

{if $mySurvey->getInfo("description") != ""}
<div class="helper">
	{$mySurvey->getInfo("description")}
</div>
{/if}
##KS_ANSWERSOF## {userlink user=$currentUser showpicture=true}

<table border="1">
	{* Dealing with users answers *}
	{foreach name="answers" from=$answers item="answerversions" key="userid"}
		
		{*
		{if $smarty.foreach.answers.first}
	<tr>
			{foreach from=$answerversions key=key item=item}
		<td>
				{assign var=question value=$mySurvey->getQuestionById($key)}
				{$question->getInfo("name")}
		</td>
			{/foreach}
		<td>
			##KS_DATE##
		</td>
	</tr>
		{/if}
		*}

		{* Dealing with versions *}
		{foreach from=$answerversions key="versionid" item="answerversion"}
		<tr>
			{foreach from=$answerversion key="key" item="item"}
			<td>
				{$item->getInfo("value")}
			</td>
			{/foreach}
			<td>
				{$item->getInfo("datetime")}
			</td>
		</tr>
		{/foreach}
	
	{/foreach}
</table>