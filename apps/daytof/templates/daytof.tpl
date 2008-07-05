<h3 class="handle">##DAYTOF_TITLE##</h3>

<script language="javascript">
var dayTofUrls = ['{kurl app="daytof" page="datof" tofnum="0"}', '{kurl app="daytof" page="datof" tofnum="1"}', '{kurl app="daytof" page="datof" tofnum="2"}'];
var dayTofUrlIndex = 0;
{literal}
function updateDayTof () {
	new Ajax.Updater('daTofContainer', dayTofUrls[dayTofUrlIndex]);
	dayTofUrlIndex++;
	if (dayTofUrlIndex >= dayTofUrls.length)
	dayTofUrlIndex = 0;
}
new PeriodicalExecuter(function (pe) { updateDayTof(); }, 30);
updateDayTof();

{/literal}
</script>

<span id="daTofContainer"></span>
<div align="center">
<a href="{kurl app="daytof" page="history"}">##DAYTOF_HISTORY##</a>
</div>
