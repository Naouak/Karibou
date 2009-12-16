var offsetxpoint = -60; // Customize x offset of tooltip
var offsetypoint = 20; // Customize y offset of tooltip

var hintEnabled = false;
var hintObject = null;
var hintSource = "A";
Event.observe(window, "load", function() {
    hintObject = document.createElement("div");
    hintObject.setAttribute("id", "hintbox");
    document.body.appendChild(hintObject);
});

Event.observe(document, "mousemove", positiontip);

function showhint (diplayText, displayClass, e) {
    if (hintObject) {
        hintSource = "A";
	if (!e) {
            e = window.event;
	}
        if (e) {
            if (e.element()) {
                hintSource = e.element().nodeName;
	    }
	}
        if (typeof displayClass!="undefined" && displayClass!="") {
            hintObject.className = 'hint ' + displayClass;
        }
        hintObject.innerHTML = diplayText;
        hintEnabled = true;
    }
}

function positiontip (e) {
    if (e.element().nodeName != hintSource) {
        hidehint();
    }
    if (hintEnabled) {
        var curX = e.pointerX();
        var curY = e.pointerY();
        
        hintObject.style.visibility = "hidden";
        hintObject.style.display = "block";	

        // Find out how close the mouse is to the corner of the window
        var rightedge = document.viewport.getWidth() - curX - offsetxpoint;
        var bottomedge = document.viewport.getHeight() - curY - offsetypoint;
        var leftedge = (offsetxpoint < 0) ? -offsetxpoint : -1000;
        
        // If the horizontal distance isn't enough to accomodate the width of the context menu
        if (rightedge < hintObject.offsetWidth) {
            // Move the horizontal position of the menu to the left by it's width
            hintObject.style.left = curX - hintObject.offsetWidth+"px";
        }  else if (curX < leftedge) {
            hintObject.style.left = "5px";
        } else {
            // Position the horizontal position of the menu where the mouse is positioned
            hintObject.style.left = curX + offsetxpoint + "px";
        }
        
        // Same concept with the vertical position
        if (bottomedge < hintObject.offsetHeight) {
            hintObject.style.top = curY - hintObject.offsetHeight - offsetypoint + "px";
        } else {
            hintObject.style.top = curY + offsetypoint + "px";
        }
        hintObject.style.visibility = "visible";
    } else {
        hidehint();
    }
}

function hidehint () {
    if (hintObject) {
        hintEnabled = false;
	hintObject.style.display = "none";
    }
}
