{include file='common.tpl'}
<script type="text/javascript">
{literal}

var found;
var j;
function getHtml(i){
	return normalHtml(i);
}

function showUser(i){
	if(users[i]['marker']){
		map.setCenter(users[i]['marker'].getPoint());
		users[i]['marker'].openInfoWindowHtml(getHtml(i));
	}
}

function showNext(){
	j++;
	if(j < found.length ){
		showUser(found[j]);
	}
}

function search(name){
	found = new Array();
	j = 0;
	for(var i = 0; i < users.length; i++){
		tmpname = users[i]['firstname'].toLowerCase()+" "+users[i]['lastname'].toLowerCase()+" "+users[i]['login'];
		if(tmpname.indexOf(name.toLowerCase()) != -1){
			found.push(i);
		}
	}
	if(found.length > 1){
		showUser(found[j]);
	}else{
		if(users[found[0]]){
			showUser(found[0]);
		}else
			alert(name + " non trouve");
	}
}

function loadedAction(){
	if(user)
		search(user);
}
{/literal}
</script>
<h1>##GEOLOC_TITLE##</h1>
<div class="form">
<input type="text" id="searchform" value="##FIND_SMBDY##" onclick="this.value='';"> <input type="submit" value="##FIND##" onclick="search(document.getElementById('searchform').value)">
</div>
<div id="map" style="height : 500px; width: 100%;">
</div>
<script type="text/javascript">
load('normal');
</script>
