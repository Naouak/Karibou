// Base class for every karibou application
var KApp = Class.create({
	initialize: function (appName, mainContainer) {
		alert("Creating a new KApp");
		this.mainContainer = mainContainer;
		this.appName = appName;
		truc = document.createElement("span");
		truc.innerHTML = "Hello from KApp";
		this.mainContainer.appendChild(truc);
		this.mainContainer.setAttribute("kapp", true);
	},
	getElementById: function (id) {
		function checkNode(subNode) {
			if (subNode.nodeType != 1)
				return;
			if (subNode.id == id)
				return subNode;
			for (var i = 0 ; i < subNode.childNodes.length ; i++) {
				childNode = subNode.childNodes[i];
				result = checkNode(childNode);
				if (result)
					return result;
			}
			return;
		}
		return checkNode(this.mainContainer);
	}
});


// Class responsible for loading the KApp classes, handling the various loaded applications on the screen...
var Karibou = Class.create({
	initialize: function(appListingUrl, appContentUrl, appJSUrl) {
		this.appListingUrl = appListingUrl;
		this.appContentUrl = appContentUrl;
		this.appJSUrl = appJSUrl;
		this.applicationsClass = new Array();
		this.loadedApplicationsJS = new Array();
		this.appLoaders = new Array();
		this.appObjs = new Array();
	},
	loadApplicationView: function(application, container) {
		req = new Ajax.Updater(container, this.appContentUrl + application, {asynchronous: true, onComplete: function (transport) {
			this.karibou.applicationViewLoaded(application);
		}});
		req.karibou = this;
	},
	applicationViewLoaded: function (application) {
		for (var i = 0 ; i < this.appLoaders.length ; i++) {
			appLoader = this.appLoaders[i];
			appLoader.handlerMain(application);
			if (appLoader.loaded()) {
				this.appLoaders = this.appLoaders.without(appLoader);
			}
		}
	},
	loadApplicationJS: function(application) {
		if ((this.loadedApplicationsJS[application]) && (this.loadedApplicationsJS[application] != Karibou.APP_FAILURE)) {
			// JS already loaded, so we must notify the apploader that its js is ready.
			if (this.loadedApplicationsJS[application] == Karibou.APP_LOADED)
				this.applicationJSLoaded(application);
		} else {
			// This is where the fun begins...
			this.loadedApplicationsJS[application] = Karibou.APP_LOADING;
			JSurl = this.appJSUrl + application;
			scriptNode = document.createElement("script");
			scriptNode.setAttribute("language", "javascript");
			scriptNode.setAttribute("src", JSurl);
			document.body.appendChild(scriptNode);
		}
	},
	applicationJSLoaded: function(application) {
		// This is called to notify that the JS part of an application has been loaded.
		this.loadedApplicationsJS[application] = Karibou.APP_LOADED;
		for (var i = 0 ; i < this.appLoaders.length ; i++) {
			appLoader = this.appLoaders[i];
			appLoader.handlerJS(application);
			if (appLoader.loaded()) {
				this.appLoaders = this.appLoaders.without(appLoader);
			}
		}
	},
	registerApplicationClass: function (applicationName, applicationClass) {
		this.applicationsClass[applicationName] = applicationClass;
	},
	getApplicationClass: function (applicationName) {
		return this.applicationsClass[applicationName];
	},
	instanciateApplication: function (applicationName, container) {
		var appLoader = new KAppLoader(applicationName, container, this);
		this.appLoaders.push(appLoader);
		appLoader.load();
	},
	registerAppObj: function (app) {
		this.appObjs.push(app);
	},
	getAppFromNode: function (node) {
		if (node == document.body) {
			return;
		}
		if (node.attributes.getNamedItem("kapp")) {
			for (var i = 0 ; i < this.appObjs.length ; i++) {
				if (this.appObjs[i].mainContainer == node)
					return this.appObjs[i];
			}
			return;
		} else if (node.parentNode)
			return this.getAppFromNode(node.parentNode);
	}
});

var KAppLoader = Class.create({
	initialize: function(applicationName, container, karibou) {
		this.jsloaded = false;
		this.mainloaded = false;
		this.appName = applicationName;
		this.container = container;
		this.karibou = karibou;
		this.targetNode = document.createElement("div");
	},
	load: function () {
		this.karibou.loadApplicationJS(this.appName);
		this.karibou.loadApplicationView(this.appName, this.targetNode);
	},
	loaded: function () {
		return this.jsloaded && this.mainloaded;
	},
	onHandler: function () {
		if (this.loaded()) {
			klass = this.karibou.getApplicationClass(this.appName);
			var appObj = new klass(this.appName, this.targetNode);
			this.container.appendChild(this.targetNode);
			this.karibou.registerAppObj(appObj);
		}
	},
	handlerJS: function (application) {
		if (this.appName == application)
			this.jsloaded = true;
		this.onHandler();
	},
	handlerMain: function (application) {
		if (this.appName == application)
			this.mainloaded = true;
		this.onHandler();
	}
});

Karibou.APP_LOADING = 0;
Karibou.APP_LOADED = 1;
Karibou.APP_FAILURE = 2;

