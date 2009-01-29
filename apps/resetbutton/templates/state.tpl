{if $islogged}
<input type="button" id="RBbutton" onclick="$app(this).resetButton();" value="##RESET##" />
<br />
{/if}

<span id="resethour">{$resethour}</span><br />
##LASTRESETBY## {userlink user=$lastresetby showpicture=$islogged}
