<p>
<strong>{$question}</strong>
<ul>
{foreach key=answerText item=voteResult from=$results}
<li>{$answerText}</li>
<span style="width: {math equation="x*100/y" x=$voteResult y=$countVotes format="%.0f"}%; background-color: #BAEDFE; position: absolute">{math equation="x*100/y" x=$voteResult y=$countVotes format="%.2f"}%</span><br />
{/foreach}
</ul>
<i>{$countVotes} votes</i>
</p>