<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key={$gkey}"
            type="text/javascript"></script>
<script type="text/javascript">
{$search}
{literal}
var map;   
var geocoder = new GClientGeocoder();
var mode;
var users = new Array();

function getUsers(){
	GDownloadUrl("{/literal}{kurl app="geoloc" page="data"}{literal}", function(data, responseCode) {
			var xml = GXml.parse(data);
			var usrs = xml.documentElement.getElementsByTagName("user");
			for (var i = 0; i < usrs.length; i++) {
				var children = usrs[i].childNodes;
				users[i] = new Array();
				for(var j=0;j < children.length; j++){
					var name = children[j].nodeName;
					users[i][name] = children[j].textContent;	
				}
			}
			addMarkers();
	});
}

function load(mymode){
	getUsers();
	mode = mymode;
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));
		if(mode == "normal"){
			map.setCenter(new GLatLng(50.636615,3.065357), 4);
		}else{
			map.setCenter(new GLatLng(25.641526,-14.589844), 1);
		}
	}
	map.addControl(new GSmallMapControl());
	if(mode == "normal"){
		map.addControl(new GMapTypeControl());
	}
	body = document.getElementsByTagName("body");
	body[0].onunload = function (){ GUnload();};

}

function addMarkers(){
	for(var i=0; i < users.length; i++){
		addMarker(i);
	}
}

function markerLoaded(i){
	if(i == users.length - 1){
		loadedAction();
	}
}

function getAddress(i){
	return users[i]['street']+' '+users[i]['extaddress']+' '+users[i]['city']+' '+users[i]['postcode']+' '+users[i]['country'];
}	

function normalHtml(i){
	ret = "<div class=\"geoimg\"><img src=\""+users[i]['picture']+"\"/></div>";
	ret += "<h1>"+users[i]['firstname']+" "+users[i]['lastname']+"</h1>";
	ret += "<div class=\"addr\"><span>"+users[i]['street']+"</span>";
	ret += "<span>"+users[i]['extaddress']+"</span>";
	ret += "<span>"+users[i]['city']+"</span>";
	ret += "<span>"+users[i]['postcode']+"</span>";
	ret += "<span>"+users[i]['country']+"</span>";
	if(found && found.length > 1 && j != found.length - 1)
		ret += "<span><a onclick=\"showNext()\">##NEXT_ADDR##</a></span></div>";
	return ret;
}

function smallHtml(i){
	ret = "<span class=\"smallgeotitle\">"+users[i]['firstname']+" "+users[i]['lastname']+"</span>"
	ret += "<span class=\"smallgeotitle\">"+users[i]['city']+", "+users[i]['country']+"</span>";
	return ret;
}

function addMarker(i) {
	var point = new GLatLng(users[i]['coords'].split(",")[0],users[i]['coords'].split(",")[1]);
	var marker = new GMarker(point);
	users[i]['marker']=marker;
	map.addOverlay(marker);
	GEvent.addListener(marker, "click", function(marker, point) {
			map.setCenter(this.getPoint());
			if(mode == "normal"){
			this.openInfoWindowHtml(getHtml(i));
			}else{
			document.getElementById("geoname").innerHTML=getHtml(i);
			}
		});
	markerLoaded(i);
}
{/literal}
</script>
<style>
{literal}
div.geoimg {
 float : left;
 margin-right : 5px;
}

div.smallgeoimg img {
	width : 20px;
	height : 30px;
}

div.addr {
  float : left;
}

div.addr span {
	display : block;
}

span.smallgeotitle {
	font-size : 9px;
	display : block;
}

div.form input{
	border : 1px solid black;
	background-color : lightgrey;
	margin : 5px 0px;
}
{/literal}
</style>
