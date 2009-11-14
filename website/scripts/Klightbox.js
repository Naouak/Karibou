YUI({
    base: "/scripts/yui/build/",
    modules:{
	"gallery-lightbox":{
	    name: "lightbox-js",
	    type:"js",
	    fullpath: "/scripts/yui/gallery-lightbox/gallery-lightbox-min.js",
	    requires: ["widget", "widget-position-ext", "widget-stack","node","event"]
	}
    }
}).use("anim","gallery-lightbox","gallery-banner","event",function(Y){
    Y.on("domready",function(){
	var LB = new Y.Lightbox({
	    selector: ".lightbox"
	});
	LB.render();
    });
});

