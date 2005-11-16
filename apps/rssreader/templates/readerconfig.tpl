<h3>News Feed Config</h3>
News Feed : <input type="text" name="feed" value="{$feed}" /><br />
Number of Element :
<select name="max">
	<option value="3" {if $max==3} SELECTED{/if}>3</option>
	<option value="5" {if $max==5} SELECTED{/if}>5</option>
	<option value="7" {if $max==7} SELECTED{/if}>7</option>
	<option value="9" {if $max==9} SELECTED{/if}>9</option>
</select>