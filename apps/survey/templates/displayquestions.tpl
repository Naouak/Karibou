{$mySurvey->getAllQuestions()|@count} ##KS_QUESTION##{if $mySurvey->getAllQuestions()|@count > 0}##KS_S##{/if}

<form method="post" action="{kurl action="saveanswers"}">
	<ul>
	{foreach from=$mySurvey->getAllQuestions() item=question}
		<li>
			<label for="ks_q{$question->getInfo("id")}">{$question->getInfo("name")}</label>
			<input name="ks_q{$question->getInfo("id")}" value="{$mySurvey->getAnswerById($question->getInfo("id"))}" />
			<br />
			<em>{$question->getInfo("description")}</em>
		</li>
	{/foreach}
	</ul>
	<input type="hidden" name="surveyid" value="{$mySurvey->getInfo("id")}" />
	<input type="submit" />
</form>