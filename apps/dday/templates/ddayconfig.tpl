<h3>{t}D Day{/t}</h3>
{if $admin} 
{t}Event{/t} <input type="text" name="ddayevent" value="" size="10" /><br />
{t}Date (format: YYYY-MM-DD){/t} <input type="text" name="ddaydate" value="" size="10" maxlength="10" /><br />
{t}Description{/t} <input type="text" name="ddaydesc" value="" size="30" /><br />
{t}Link{/t} <input type="text" name="ddaylink" value="" size="30" /><br />
<br />
{else}
{t}You don't have sufficient privileges to add an event{/t}
{/if}
