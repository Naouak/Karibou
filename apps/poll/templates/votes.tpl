<p>
<strong>{$question}</strong>
<ul>
{foreach key=answerText item=voteResult from=$results}
<li>{$answerText}</li>
<span style="width: {$voteResult*100/$countVotes|@round}%; background-color: #BAEDFE; position: absolute">{$voteResult*100/$countVotes|@round}%</span><br />
{/foreach}
</ul>
<i>{$countVotes} ##VOTES##</i>
</p>