<h1>##SURVEY##</h1>
{if ($admin)}
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
			{if $smarty.foreach.answers.first}
		<tr>
			<th>
					##KS_VERSIONID##
			</th>
				{foreach from=$mySurvey->getAllQuestions() key=key item=question}
			<th>
					{$question->getInfo("name")}
			</th>
				{/foreach}
			<th>
				##KS_DATE##
			</th>
		</tr>
			{/if}
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
				<td>
					{$versionid}
				</td>
				{foreach from=$answerversion key="key" item="item"}
				<td>
					{if $item->getInfo("value") != ""}{$item->getInfo("value")}{else}&nbsp;{/if}
				</td>
				{/foreach}
				<td>
					{if $item->getInfo("datetime") != ""}{$item->getInfo("datetime")}{else}&nbsp;{/if}
				</td>
			</tr>
			{/foreach}
		
		{/foreach}
	</table>
{else}
	##KS_NOPERMISSION##
{/if}