YUI.add("gallery-lightbox",function(C){try{var B=function(D){B.superclass.constructor.call(this,D);};B.NAME="lightbox";B.ATTRS={selector:{value:"a[rel=lightbox]"},anim:{value:!C.Lang.isUndefined(C.Anim)},strings:{value:{closecaption:"click to close",close:"Close"}}};C.extend(B,C.Widget,{initializer:function(){this.set("zIndex",10000);this.publish("openLB",{defaultFn:function(){}});this.publish("closeLB",{defaultFn:function(){}});C.one("body").delegate("click",C.bind(function(E){try{if(this.imgnode!==undefined){this.get("contentBox").removeChild(this.imgnode);}}catch(D){}try{if(this.titlenode!==undefined){this.get("contentBox").removeChild(this.titlenode);}}catch(D){}this.fire("openLB",{src:E.currentTarget.get("href"),title:E.currentTarget.get("title"),iframe:E.currentTarget.hasClass("lightbox-iframe")});E.preventDefault();},this),this.get("selector"));this.hide();},destructor:function(){this.imgnode=null;this.detach("*");},renderUI:function(){this.loadingImg=C.Node.create("<div class='"+this.getClassName("loading")+"'></div>");this.closebutton=C.Node.create("<a href=''>"+this.get("strings.close")+"</a>");},bindUI:function(){this.after("openLB",C.bind(this._openLB,this));this.after("closeLB",C.bind(this._closeLB,this));this.closebutton.on("click",C.bind(function(D){this.fire("closeLB");D.preventDefault();},this));},syncUI:function(){},_loadLB:function(){this.get("contentBox").appendChild(this.loadingImg);if(this.get("anim")){var D=new C.Anim({node:this.get("boundingBox"),from:{opacity:0},to:{opacity:1},duration:0.1});D.run();}this.show();this.set("centered",true);},_loadContent:function(D){this.titlenode=C.Node.create("<h1 class='"+this.getClassName("title")+"'>"+D.title+"</h1>");this.iframe=D.iframe;if(D.iframe){this.imgnode=C.Node.create("<iframe src='"+D.src+"' class='"+this.getClassName("iframe")+"' />");}else{this.imgnode=C.Node.create("<img />");this.imgnode.set("title",this.get("strings").closecaption);this.imgnode.set("src",D.src);}},_resizeLB:function(){if(this.iframe){var E=this.imgnode.get("offsetWidth"),G=this.imgnode.get("offsetHeight");var I=this.get("boundingBox").get("offsetHeight"),D=this.get("boundingBox").get("winHeight");var F=this.get("boundingBox").get("offsetWidth"),J=this.get("boundingBox").get("winWidth");this.imgnode.set("width",E+0.7*J-F);this.imgnode.set("height",G+0.7*D-I);}else{var E=this.imgnode.get("offsetWidth"),G=this.imgnode.get("offsetHeight"),H=E/G;var I=this.get("boundingBox").get("offsetHeight"),D=this.get("boundingBox").get("winHeight");if(D-I<0){G+=(D-I);E+=(D-I)*H;}this.imgnode.set("width",E);this.imgnode.set("height",G);var F=this.get("boundingBox").get("offsetWidth"),J=this.get("boundingBox").get("winWidth");if(J-F<0){E+=(J-F);G+=(J-F)/H;}this.imgnode.set("width",E);this.imgnode.set("height",G);}},_fromLoadToContent:function(){this.hide();this.get("contentBox").removeChild(this.loadingImg);this.get("contentBox").appendChild(this.imgnode);this.get("contentBox").appendChild(this.titlenode);this.get("contentBox").appendChild(this.closebutton);if(this.get("anim")){var D=new C.Anim({node:this.get("boundingBox"),from:{opacity:0},to:{opacity:1},duration:0.1});D.on("start",C.bind(this.show,this));D.run();}else{this.show();}this._resizeLB();this.set("centered",true);},_openLB:function(D){this._loadLB();this._loadContent(D);if(this.iframe){this._fromLoadToContent();}else{this.imgnode.on("load",C.bind(function(){this._fromLoadToContent();},this));this.imgnode.on("error",C.bind(function(){this.fire("closeLB");},this));}if(!this.iframe){this.imgnode.on("click",C.bind(function(){this.fire("closeLB");},this));}},_closeLB:function(){if(this.get("anim")){var D=new C.Anim({node:this.get("boundingBox"),from:{opacity:1},to:{opacity:0},duration:0.1});D.on("end",C.bind(this.hide,this));D.run();}else{this.hide();}}});B=C.Base.build(B.NAME,B,[C.WidgetPosition,C.WidgetPositionExt,C.WidgetStack],{dynamic:false});C.Lightbox=B;}catch(A){alert(A.message);}},"@VERSION@",{requires:["widget","widget-position-ext","widget-stack","node","event"]});