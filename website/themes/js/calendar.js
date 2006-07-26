
var KCalendar = {
	
	startHour : 0,
	endHour : 24,	
	size : 2, // do not change it for now !
	
	sessionstarted : false,
	selectionStart : 0,
	selectionEnd : 0,
	
	cells : null,	
	
	startSelect : function(event) {
		this.clearSelected();
		this.sessionstarted = true;
		this.selectionStart = this.fetchIndexAt( event.pageX , event.pageY );
		this.selectionEnd = this.selectionStart;
		var cell = this.fetchCellAtIndex(this.selectionStart);
		Element.addClassName(cell, "selected");
	},

	endSelect : function(event, url) {
		this.sessionstarted = false;
		var cell = this.fetchCellAt( event.pageX , event.pageY );
		//Element.removeClassName(cell, "selected");
		//alert(this.selectionStart+"/"+this.selectionEnd);
		
		var queryComponents = new Array();
		
		start_date.hour = Math.floor((this.selectionStart)/this.size) ;
		start_date.minute = (this.selectionStart%this.size) *(60/this.size);
		stop_date.hour = Math.floor((this.selectionEnd+1)/this.size) ;
		stop_date.minute = ((this.selectionEnd+1)%this.size) *(60/this.size);
		
		for( i in start_date )
		{
			queryComponents.push(
				"start" + encodeURIComponent(i) + "=" + 
				encodeURIComponent(start_date[i]) );
		}
		for( i in stop_date )
		{
			queryComponents.push(
				"stop" + encodeURIComponent(i) + "=" + 
				encodeURIComponent(stop_date[i]) );
		}
		var post_vars = queryComponents.join("&");

		new Ajax.Updater("divEditEvent", url, {
			asynchronous:true,
			evalScripts:true,
			method:'post',
			postBody: post_vars
		});

		Element.removeClassName($("divEditEvent"), "hide");
		Element.addClassName($("divEditEvent"), "show");
		this.clearSelected();
	},

	mouseMove : function(event) {
		if( ! this.sessionstarted ) return;
		//this.selectTo(this.fetchIndexAt( event.pageX , event.pageY ));
		this.select(this.fetchIndexAt( event.pageX , event.pageY ));
	},
	
	select : function(index) {
		if( index == -1 ) return;
		if( this.selectionEnd == index ) return;
		this.selectionEnd = index;
		var start;
		var end;
		if( this.selectionStart < this.selectionEnd )
		{
			start = this.selectionStart;
			end = this.selectionEnd;
		}
		else
		{
			start = this.selectionEnd;
			end = this.selectionStart;
		}
		for( var i=0 ; i < this.cells.length ; i++ )
		{
			if( (start <= i) && (i <= end) )
			{
				Element.addClassName(this.cells[i], "selected");
			}
			else
			{
				Element.removeClassName(this.cells[i], "selected");
			}
		}
	},
	
	selectTo : function( index ) {
		//if( this.selectionEnd != index )
		{
			if( index < this.selectionEnd )
			{
				if( this.selectionStart <= index )
				{
					for( var i=index+1 ; i<=this.selectionEnd ; i++)
					{
						var cell = this.fetchCellAtIndex(i);
						Element.removeClassName(cell, "selected");
					}
				}
				else
				{
					for( var i=index ; i<=this.selectionEnd ; i++)
					{
						var cell = this.fetchCellAtIndex(i);
						Element.addClassName(cell, "selected");
					}
				}
			}
			else // index > this.selectionEnd
			{
				if( this.selectionStart <= index )
				{
					for( var i=this.selectionEnd ; i<=index ; i++)
					{
						var cell = this.fetchCellAtIndex(i);
						Element.addClassName(cell, "selected");
					}
				}
				else
				{
					for( var i=this.selectionEnd ; i<index ; i++)
					{
						var cell = this.fetchCellAtIndex(i);
						Element.removeClassName(cell, "selected");
					}
				}
			}
			this.selectionEnd = index;
		}
	},
	
	clearSelected : function () {
		for( var i=0 ; i < this.cells.length ; i++ )
		{
			Element.removeClassName(this.cells[i], "selected");
		}
	},	
	
	fetchIndexAt : function( X , Y ) {
		var calY = Y - this.calendar.offsetParent.offsetTop + this.calendar.parentNode.scrollTop;
		for( var i=0 ; i < this.cells.length ; i++ )
		{
			if( ( this.cells[i].offsetTop < calY ) 
				&& (calY < (this.cells[i].offsetTop+this.cells[i].offsetHeight)) )
			{
				return i;
			}
		}
		return -1;
	},

	fetchCellAtIndex : function( index ) {
		if( index == -1 ) return null;
		return this.cells[index];
	},
	
	fetchCellAt : function( X , Y ) {
		return this.fetchCellAtIndex(this.fetchIndexAt( X , Y ));
	},
	
	createHours : function ( elementid ) {
		var calendar = $( elementid );

		var div;
		var text;
		var id;
		var timespanclass = "";
		for( var h = this.startHour ; h < this.endHour ; h++ )
		{
			if( (7 < h) && (h < 19) )
			{
				timespanclass = "workingtimespan ";
			}
			else
			{
				timespanclass = "timespan ";
			}
			id = h - this.startHour;
			div = document.createElement("div");
			text = document.createTextNode(h);
			div.appendChild(text);
			div.setAttribute("id", "calhour-"+id);
			div.setAttribute("class", timespanclass+"hour size_1_00");
			calendar.appendChild(div);
			
		}
		calendar.parentNode.scrollTop = document.getElementById("calhour-8").offsetTop;
	},

	createSelect : function ( elementid, url ) {
		this.calendar = $( elementid );
		this.calendar.setAttribute("onmousedown", "KCalendar.startSelect(event);");
		this.calendar.setAttribute("onmouseup", "KCalendar.endSelect(event, '"+url+"');");
		this.calendar.setAttribute("onmousemove", "KCalendar.mouseMove(event);");

		var div;
		var id;
		for( var h = this.startHour ; h < this.endHour ; h++ )
		{
			id = h - this.startHour;
			div = document.createElement("div");
			div.setAttribute("id", "cal-"+id+"-0");
			div.setAttribute("class", "size_0_30");
			this.calendar.appendChild(div);
			
			div = document.createElement("div");
			div.setAttribute("id", "cal-"+id+"-1");
			div.setAttribute("class", "size_0_30");
			this.calendar.appendChild(div);

		}
		this.cells = this.calendar.getElementsByTagName("div");
	}

}
