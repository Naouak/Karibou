var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip

var dall=document.all
var geid=document.getElementById && !document.all

var enablehint=false

if (dall||geid)
{
	var hintobject = document.all? document.all["hintbox"] : document.getElementById? document.getElementById("hintbox") : ""
}

function truebody()
{
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function showhint(diplayText, displayClass)
{
	if (geid||dall)
	{
	/*
		if (typeof thewidth!="undefined")
		{
			tipobj.style.width=thewidth+"px"
		}
	*/
		
		if (typeof displayClass!="undefined" && displayClass!="")
		{
			hintobject.className = 'hint ' + displayClass
		}
		
		hintobject.innerHTML = diplayText
		enablehint = true
		return false
	}
}

function positiontip(e)
{
	if (enablehint)
	{
		var curX=(geid)?e.pageX : event.clientX + truebody().scrollLeft;
		var curY=(geid)?e.pageY : event.clientY + truebody().scrollTop;
		
		//Find out how close the mouse is to the corner of the window
		var rightedge = dall&&!window.opera? truebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
		var bottomedge = dall&&!window.opera? truebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20
		
		var leftedge = (offsetxpoint<0)? offsetxpoint*(-1) : -1000
		
		//if the horizontal distance isn't enough to accomodate the width of the context menu
		if (rightedge < hintobject.offsetWidth)
		{
			//move the horizontal position of the menu to the left by it's width
			hintobject.style.left=dall? truebody().scrollLeft + event.clientX - hintobject.offsetWidth+"px" : window.pageXOffset+e.clientX - hintobject.offsetWidth+"px"
		}
		else if (curX<leftedge)
		{
			hintobject.style.left="5px"
		}
		else
		{
			//position the horizontal position of the menu where the mouse is positioned
			hintobject.style.left = curX + offsetxpoint + "px"
		}
		
		//same concept with the vertical position
		if (bottomedge < hintobject.offsetHeight)
		{
			hintobject.style.top=dall? truebody().scrollTop + event.clientY - hintobject.offsetHeight - offsetypoint+"px" : window.pageYOffset + e.clientY - hintobject.offsetHeight - offsetypoint+"px"
		}
		else
		{
			hintobject.style.top = curY+offsetypoint + "px"
			hintobject.style.visibility = "visible"
		}
	}
}

function hidehint(){
	if (geid||dall){
		enablehint = false
		hintobject.style.visibility="hidden"
		hintobject.style.backgroundColor=''
		hintobject.style.width=''
		hintobject.style.myClass='hint'
	}
}

document.onmousemove=positiontip