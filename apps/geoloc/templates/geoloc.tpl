<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={$gkey}"
            type="text/javascript"></script>
<script type="text/javascript">
{$jscript_table}
{literal}
var map;   
var geocoder = new GClientGeocoder();
function load(){
if (GBrowserIsCompatible()) {
	map = new GMap2(document.getElementById("map"));
	map.setCenter(new GLatLng(47.15, 19.69), 4);
}
for(var i=0; i < adresses.length; i++){
	showAddress(adresses[i]);
}
map.addControl(new GSmallMapControl());

}

function showAddress(address) {
  geocoder.getLatLng(
    address,
    function(point) {
      if (point) {
        //map.setCenter(point, 13);
        var marker = new GMarker(point);
        map.addOverlay(marker);
        //marker.openInfoWindowHtml(address);
		GEvent.addListener(marker, "click", function(marker, point) {
			 this.openInfoWindowHtml("<h1>Test</h1>");
			if(marker){
			 alert("Got click");
			}
			});
      }
    }
  );
}
{/literal}
</script>
<h1>Geoloc</h1>
<div id="map" style="height : 500px; width: 1000px;">
</div>
<script type="text/javascript">
load();
</script>
