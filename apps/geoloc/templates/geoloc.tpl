<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAA6Hj8UyWfS2vgHLH5xUnjcRS2pPd6e2CR9F2af-lw7lyMsqFWZhTe7LS1-y8ObG7SDSoGt4cdhXq2Gg"
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
			if(marker){
			 alert("Got click");
			 this.openInfoWindowHtml("<h1>Test</h1>");
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
