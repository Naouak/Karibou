<h3>##DDAY##</h3>
{if $admin} 
##DDAYEVENT##<input type="text" name="ddayevent" value="" size="10"/><br />
##DDAYDATE##<input type="text" name="ddaydate" value="" size="10" maxlength="10" /><br />
##DDAYDESC##<input type="text" name="ddaydesc" value="" size="30"/><br />
<br />
{else}
##DDAYPERM##
{/if}