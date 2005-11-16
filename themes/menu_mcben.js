var menuActif = false;
var clickMenu = false;

function Chargement() {
	for(i=1;i<=3;i++) {
		// correction pour IE
		tempLeft = (document.getElementById("menu"+i).offsetLeft)

		with(document.getElementById("ssmenu"+i).style) {
			position="absolute";
			left = tempLeft+"px";
			top = (document.getElementById("menu"+i).offsetTop+document.getElementById("menu"+i).offsetHeight+10)+"px";
			zIndex="3";
		}
	}
	CacherMenus();
}

function MontrerMenu(strMenu)
{
	if(menuActif)
	{
		CacherMenus();
		document.getElementById(strMenu).style.visibility="visible";
	}
}

function CacherMenus() {
    for(i=1;i<=3;i++) {
      with(document.getElementById("ssmenu"+i).style) {
        visibility="hidden";
      }
    }
}

function body_onclick()
{
	if(menuActif && !clickMenu)
	{
		menuActif = false;
		CacherMenus();
	}
	clickMenu=false;
}

function menu_onclick(strMenu)
{
	clickMenu = true;
	if(!menuActif)
	{
		CacherMenus();
		document.getElementById(strMenu).style.visibility="visible";
		menuActif = true;
	}
	else
	{
		CacherMenus();
		menuActif = false;
	}
}
