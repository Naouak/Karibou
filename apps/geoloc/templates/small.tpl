{include file='common.tpl'}
<script type="text/javascript">
{literal}
function getHtml(i){
	return smallHtml(i);
}
{/literal}
</script>
<h3 class="handle">##GEOLOC_TITLE##</h3>
<div id="map" style="height : 200px; width: 100%;">
</div>
<div id="geoname" style="height : 30px;">
</div>
<script type="text/javascript">
load('mini');
</script>
