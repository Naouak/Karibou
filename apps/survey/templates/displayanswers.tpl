<h1>##SURVEY##</h1>
<h2>{$mySurvey->getInfo("name")}</h2>

{if $mySurvey->getInfo("description") != ""}
<div class="helper">
	{$mySurvey->getInfo("description")}
</div>
{/if}

<table border="1">
	{foreach name="answers" from=$answers item="answer"}
		{if $smarty.foreach.answers.first}
			{foreach from=$answer key=key item=item}
		<td>
				{$mySurvey->getQuestionById($key)->getInfo("name")}
		<td>
			{/foreach}
		{/if}
	<tr>
	
		{foreach from=$answer key=key item=item}
		<td>
			{$item->getInfo("value")}
		<td>
		{/foreach}
	</tr>
	{/foreach}
</table>