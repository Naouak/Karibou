<div class="default2-ressetbutton-state">

<div class="default2-resetbutton-hour" id="resethour">{$resethour}</div>

{if $islogged}
<input type="button" class="default2-resetbutton-button" id="RBbutton" onclick="$app(this).resetButton();" value="##RESET##" />
{/if}

##LASTRESETBY## {userlink user=$lastresetby showpicture=$islogged}
</div>
