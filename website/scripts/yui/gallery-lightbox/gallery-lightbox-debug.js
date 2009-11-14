YUI.add('gallery-lightbox', function(Y) {

/**
 * Lightbox Module
 * @module lightbox
 * @requires widget,widget-position-ext,widget-stack,node,event
 * @class Lightbox
 * @extends Widget
 * @extends WidgetPosition
 * @extends WidgetPositionExt
 * @extends WidgetStack
 * @namespace Y
 */
try{
    var Lightbox = function(conf){
	Lightbox.superclass.constructor.call(this,conf);
    };
    Lightbox.NAME = "lightbox";

    Lightbox.ATTRS = {
	/**
	 * Selector used to determines which links need to be "lightboxed"
	 * @attribute selector
	 * @default a[rel=lightbox]
	 * @type string
	 */
	selector:{
	    value: "a[rel=lightbox]"
	},
	/**
	 * Activate animation
	 * @attribute anim
	 * @type boolean
	 */
	anim:{
	    value:  !Y.Lang.isUndefined(Y.Anim)
	},
	/**
	 *	Strings for tanslation
	 *	@attribute strings
	 *	@type object
	 */
	strings:{
	    value:{
		closecaption: "click to close"
	    }
	}
    };

    Y.extend(Lightbox,Y.Widget,{
	initializer: function(){
	    this.set("zIndex",10000);

	    this.publish("openLB",{
		defaultFn: function(){
		    Y.log("Opening a LightBox");
		}
	    });
	    this.publish("closeLB",{
		defaultFn: function(){
		    Y.log("Closing a LightBox");
		}
	    });
	    Y.log("instanciating events with this selector:"+ this.get("selector"));
	    Y.one("body").delegate("click",Y.bind(function(e){
		Y.log("click !");
		if(this.imgnode !== undefined){
		    this.get("contentBox").removeChild(this.imgnode);
		}
		if(this.titlenode !== undefined){
		    this.get("contentBox").removeChild(this.titlenode);
		}
		this.fire("openLB",{
		    src : e.currentTarget.get("href"),
		    title : e.currentTarget.get("title")
		});
		e.preventDefault();
	    },this),this.get("selector"));
	    this.hide();
	},
	destructor: function(){
	    this.imgnode = null;
	    this.detach("*");
	},
	renderUI: function(){
	    this.loadingImg = Y.Node.create("<div class='"+this.getClassName("loading")+"'></div>");
	},
	bindUI: function(){
	    this.after("openLB", Y.bind(this._openLB,this));
	    this.after("closeLB",Y.bind(this._closeLB,this));
	},
	syncUI: function(){

	},
	/**
	 * Show the loading LB
	 * @method _loadLB
	 * @protected
	 * @todo cut function
	 */
	_loadLB: function(){
	    //First we add the loading div
	    this.get("contentBox").appendChild(this.loadingImg);
	    //If we are animated, we need to make it visible
	    if(this.get("anim")){
		var anim = new Y.Anim({
		    node: this.get("boundingBox"),
		    from: {
		       opacity: 0
		    },
		    to:{
			opacity: 1
		    },
		    duration: 0.1
		});
		anim.run();
	    }
	    //Then we show it and center it
	    this.show();
	    this.set("centered",true);
	},
	/**
	 * Load the LightBox Content
	 * @method _loadContent
	 * @protected
	 *
	 */
	_loadContent: function(e){
	    //Loading title
	    this.titlenode = Y.Node.create("<h1 class='"+this.getClassName("title")+"'>"+e.title+"</h1>");
	    //Loading image
	    this.imgnode = Y.Node.create("<img />");
	    this.imgnode.set("title",this.get("strings").closecaption);
	    this.imgnode.set("src",e.src);
	},
	/**
	 * Resize the image to make it fit the viewport
	 * @method _resizeLB
	 * @protected
	 */
	_resizeLB: function(){
	    //Get image information
	    var iw = this.imgnode.get("offsetWidth"),
		ih = this.imgnode.get("offsetHeight"),
		ir = iw / ih;

	    //adapt height
	    var bbh = this.get("boundingBox").get("offsetHeight"),
		wh = this.get("boundingBox").get("winHeight");
	    if(wh - bbh < 0){
		ih += (wh - bbh);
		iw += (wh - bbh)*ir;
	    }
	    this.imgnode.set("width",iw);
	    this.imgnode.set("height",ih);

	    //adapt width
	    var bbw = this.get("boundingBox").get("offsetWidth"),
		ww = this.get("boundingBox").get("winWidth");
	    if(ww - bbw < 0){
		iw += (ww - bbw);
		ih += (ww - bbw)/ir;
	    }
	    this.imgnode.set("width",iw);
	    this.imgnode.set("height",ih);
	},
	/**
	 * transition from loading state to show content state
	 * @method _fromLoadToContent
	 * @protected
	 */
	_fromLoadToContent: function(){
	    this.hide();
	    this.get("contentBox").removeChild(this.loadingImg);
	    this.get("contentBox").appendChild(this.imgnode);
	    this.get("contentBox").appendChild(this.titlenode);
	    if(this.get("anim")){
		var anim = new Y.Anim({
		    node: this.get("boundingBox"),
		    from: {
		       opacity: 0
		    },
		    to:{
			opacity: 1
		    },
		    duration: 0.5
		});
		anim.on("start",Y.bind(this.show,this));
		anim.run();
	    }
	    else{
		this.show();
	    }
	    this._resizeLB();
	    this.set("centered",true);
	},
	/**
	 * Open a LB
	 * @method _openLB
	 * @protected
	 * @todo cut function
	 */
	_openLB: function(e){
	    this._loadLB();
	    this._loadContent(e);

	    //We add it to the content box only when it's fully loaded
	    this.imgnode.on("load",Y.bind(function(){
		this._fromLoadToContent();
	    },this));
	    //We attach close on image click
	    this.imgnode.on("click",Y.bind(function(){this.fire("closeLB");},this));
	},
	/**
	 * When UI is asked to close lightbox
	 * @method _closeLB
	 * @protected
	 */
	_closeLB: function(e){
	    if(this.get("anim")){
		    var anim = new Y.Anim({
			node: this.get("boundingBox"),
			from: {
			   opacity: 1
			},
			to:{
			    opacity: 0
			},
			duration: 0.5
		    });
		    anim.on("end",Y.bind(this.hide,this));
		    anim.run();

	    }
	    else{
		this.hide();
	    }
	}

    });

    Lightbox = Y.Base.build(Lightbox.NAME, Lightbox, [Y.WidgetPosition,Y.WidgetPositionExt, Y.WidgetStack], {
	dynamic:false
    });

    Y.Lightbox = Lightbox;
}
catch(e){
    alert(e.message);
}





}, '@VERSION@' ,{requires:['widget','widget-position-ext','widget-stack','node','event']});
