// Work around a stupid scriptaculous design mistake : it requires the containers for Sortable to have a unique ID...
// Why, why do they use id instead of DOM objects...
var containerID = 0;

// Class handling drag'n'drop
var KSortable = Object.extend(Sortable, {});

// Class for a tab in the window
var KTab = Class.create({
	initialize: function (tabId, tabName, tabDiv) {
		this.tabId = tabId;
		this.tabName = tabName;
		this.tabDiv = tabDiv;
		this.resizing = false;
		this.tabsSettingsNode = document.createElement("div");
		this.tabsSettingsNode.setAttribute("class", "tabsSettings");
		this.tabDiv.appendChild(this.tabsSettingsNode);
		this.columnsContainer = document.createElement("div");
		this.columnsContainer.setAttribute("class", "colonnes");
		this.tabDiv.appendChild(this.columnsContainer);
		this.tabContainers = new Array();
		for (var i = 0 ; i < 3 ; i++) {
			var divNode = document.createElement("div");
			divNode.setAttribute("id", "container_" + containerID);
			divNode.setAttribute("class", "colonne");
			// Warning: the columns can't use 100% of the available size : they have a border...
			divNode.style.width = Math.round(99/3) + '%';
			this.columnsContainer.appendChild(divNode);
			this.tabContainers.push(divNode);
			containerID++;
		}
		this.rebuildContainers();
	},
	rebuildContainers: function () {
		for (var i = 0 ; i < this.tabContainers.length ; i++) {
			KSortable.create(this.tabContainers[i], {dropOnEmpty: true, tag: "div", overlap: "horizontal", handle: "handle", constraint: false, containment: this.tabContainers, accept: ["appRootContainer"]});
		}
	},
	destroy: function () {
		alert("Destroying a tab");
	},
	startResize: function () {
		if (this.resizing) {
			alert("You can't resize twice");
			return;
		}
		this.resizing = true;
		var slider = document.createElement("div");
		slider.setAttribute("class", "slider");
		var currentWidth = 0;
		var handles = new Array();
		var sliderValues = new Array();
		for (var i = 0 ; i < this.tabContainers.length - 1 ; i++) {
			var widthTxt = new String(this.tabContainers[i].style.width);
			currentWidth += Number(widthTxt.substring(0, widthTxt.length - 1));
			var handle = document.createElement("div");
			handle.setAttribute("class", "handle");
			sliderValues.push(currentWidth);
			slider.appendChild(handle);
			handles.push(handle);
		}
		this.tabsSettingsNode.appendChild(slider);
		var sliderObject = new Control.Slider(handles, slider, { range: $R(0, 100), tabObject: this, sliderValue: sliderValues, restricted: true, onSlide: function (value) {
			var previousSize = 0;
			for (var i = 0 ; i < value.length ; i++) {
				if ((i == value.length-1) && value[i] > 90)
					value[i] = 90;
				var colSize = value[i] - previousSize;
				if (colSize < 10)
					colSize = 10;
				this.tabObject.tabContainers[i].style.width = colSize + '%';
				previousSize += colSize;
			}
			this.tabObject.tabContainers[i].style.width = (99 - previousSize) + '%';
		}});
	}
});

// Base class for every karibou application
KApp = Class.create({
	initialize: function (appName, mainContainer, karibou) {
		this.karibou = karibou;
		this.mainContainer = mainContainer;
		this.appName = appName;
		this.mainContainer.setAttribute("kapp", true);
		id = this.appName + "_" + this.karibou.getNewIDForApp(this.appName);
		this.mainContainer.setAttribute("id", id); 
		this.appBox = this.getElementById(this.appName);
		this.appHandle = this.getElementById(this.appName + "_handle");
	},
	shade: function() {
		Effect.toggle(this.appBox, 'appear', { duration: 0.2 });
	},
	refresh: function() {
		req = new Ajax.Updater(this.mainContainer, this.karibou.appContentUrl + this.appName, {asynchronous: true, onComplete: function (transport) {
			app = transport.request.application;
			app.appBox = app.getElementById(app.appName);
		}});
		req.application = this;
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
	},
	setTitle: function (title) {
		this.appHandle.innerHTML = title;
	}
});

// Class responsible for loading the KApp classes, handling the various loaded applications on the screen...
var Karibou = Class.create({
	initialize: function(appListingUrl, appContentUrl, appJSUrl, tabLinkClicked) {
		this.appListingUrl = appListingUrl;
		this.appContentUrl = appContentUrl;
		this.appJSUrl = appJSUrl;
		this.applicationsClass = new Array();					// {app name => JS class}
		this.loadedApplicationsJS = new Array();				// [app name]
		this.appLoaders = new Array();						// [KAppLoader], the applications being loaded
		this.appObjs = new Array();						// [KApp objects]
		this.tabsContainer = document.getElementById("tabsContainer");
		this.tabsBar = document.getElementById("tabsBar");
		this.tabLinkClickedCallback = tabLinkClicked;
		this.tabs = new Array();						// [KTab objects]
		this.maxTabId = 0;
		this.currentTab = null;
		this.appIds = new Array();						// {app name => max used ID}
	},
	getNewIDForApp: function(appName) {
		if (this.appIds[appName] != undefined) {
			this.appIds[appName] = this.appIds[appName] + 1;
		} else {
			this.appIds[appName] = 0;
		}
		return this.appIds[appName];
	},
	createNewTab: function(tabName) {
		for (var i = 0 ; i < this.tabs.length ; i++) {
			if (this.tabs[i].tabName == tabName) {
				alert("Duplicate tab name");
				return;
			}
		}
		if (this.currentTab != null) {
			this.currentTab.tabDiv.style.display = "none";
			this.currentTab = null;
			for (var i = 0 ; i < this.tabsBar.childNodes.length ; i++) {
				if (this.tabsBar.childNodes[i].attributes.getNamedItem("class") != null) {
					if (this.tabsBar.childNodes[i].attributes.getNamedItem("class").nodeValue == "activeTabLink")
						this.tabsBar.childNodes[i].setAttribute("class", "");
				}
			}
		}
		var tabTitleNode = document.createElement("span");
		var aNode = document.createElement("a");
		$(aNode).observe('click', this.tabLinkClickedCallback);
		tabTitleNode.setAttribute("tabName", tabName);
		tabTitleNode.setAttribute("class", "activeTabLink");
		tabTitleNode.setAttribute("id", "tabTitle_" + this.maxTabId);
		aNode.innerHTML = tabName;
		tabTitleNode.appendChild(aNode);
		this.tabsBar.appendChild(tabTitleNode);

		var tabNode = document.createElement("div");
		tabNode.setAttribute("class", "tab home");
		tabNode.setAttribute("id", "tab_" + this.maxTabId);
		this.tabsContainer.appendChild(tabNode);
		var tabObj = new KTab(this.maxTabId, tabName, tabNode);
		this.tabs[this.maxTabId] = tabObj;
		this.currentTab = tabObj;
		this.maxTabId++;
	},
	tabLinkClicked: function (evt) {
		var elem = Event.element(evt);
		//alert(elem.parentNode.id);
		if (elem.parentNode.id.substring(0, 9) == "tabTitle_") {
			id = elem.parentNode.id.substring(9);
			this.focusTab(this.tabs[id]);
		}
	},
	closeCurrentTab: function () {
		if (this.currentTab != null) {
			tabName = this.currentTab.tabName;

			this.currentTab.destroy();
			
			newCurrentTabIdx = this.tabs.indexOf(this.currentTab) - 1;
			this.tabs = this.tabs.without(this.currentTab);
			if ((newCurrentTabIdx == -1) && (this.tabs.length > 0))
				newCurrentTabIdx = 0;
			
			this.currentTab.tabDiv.parentNode.removeChild(this.currentTab.tabDiv);

			for (var i = 0 ; i < this.tabsBar.childNodes.length ; i++) {
				if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabName") != null) {
					if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabName").nodeValue == tabName)
						this.tabsBar.childNodes[i].parentNode.removeChild(this.tabsBar.childNodes[i]);
				}	
			}
			if (newCurrentTabIdx != -1) {
				this.focusTab(this.tabs[newCurrentTabIdx].tabName);
			}
		}
	},
	focusTab: function(tabObject) {
		if (this.currentTab != null)
			this.currentTab.tabDiv.style.display="none";
		tabObject.tabDiv.style.display="block";
		this.currentTab = tabObject;
		for (var i = 0 ; i < this.tabsBar.childNodes.length ; i++) {
			if (this.tabsBar.childNodes[i].id != null) {
				if (this.tabsBar.childNodes[i].id != "tabTitle_" + tabObject.tabId)
					this.tabsBar.childNodes[i].setAttribute("class", "");
				else
					this.tabsBar.childNodes[i].setAttribute("class", "activeTabLink");
			}	
		}
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
		//@TODO : fix me !
		if (this.currentTab)
			container = this.currentTab.tabContainers[0];
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
		this.targetNode.setAttribute("class", "appRootContainer");
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
			this.container.appendChild(this.targetNode);
			var appObj = new klass(this.appName, this.targetNode, this.karibou);
			this.karibou.registerAppObj(appObj);
			if (this.karibou.currentTab != null) 
				this.karibou.currentTab.rebuildContainers();
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
