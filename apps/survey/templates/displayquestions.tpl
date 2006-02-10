{$mySurvey->getAllQuestions()|@count} ##KS_QUESTION##{if $mySurvey->getAllQuestions()|@count > 0}##KS_S##{/if}

<form method="post" action="{kurl action="saveanswers"}">
<ul>
{foreach from=$mySurvey->getAllQuestions() item=question}
	<li>
		<label for="q{$question->getInfo("id")}">{$question->getInfo("name")}</label>
		<input name="q{$question->getInfo("id")}" value="{$mySurvey->getAnswer($question->getInfo("id"))}">
		<br />
		<em>{$question->getInfo("description")}</em>
	</li>
{/foreach}
</ul>
<input type="submit">
</form>