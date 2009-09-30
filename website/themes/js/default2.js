// Work around a stupid scriptaculous design mistake : it requires the containers for Sortable to have a unique ID...
// Why, why do they use id instead of DOM objects...
var containerID = 0;

function getSubElementById(id, subNode) {
	if (subNode.nodeType != 1)
		return;
	if (subNode.id == id)
		return subNode;
	for (var i = 0 ; i < subNode.childNodes.length ; i++) {
		childNode = subNode.childNodes[i];
		result = getSubElementById(id, childNode);
		if (result)
			return result;
	}
	return;
}

var KSortable = Object.extend(Sortable, {
	serialize: function (element) {
		// This hack is needed. You can't return a constant value, and we don't care/are too lazy to write a fully featured serialize function.
		// The default serialize function doesn't handle our box ids...
		return new Date();
	}
});

// Class for a tab in the window
var KTab = Class.create({
	// Special case : if settings is not undefined, we ignore tabName, and we will load the applications....
	initialize: function (karibou, tabName, tabDiv, settings) {
		this.karibou = karibou;
		if (settings) {
			this.tabName = settings["name"];
			this.tabSizes = settings["sizes"];
		} else {
			this.tabName = tabName;
			this.tabSizes = [33,33,33];
		}
		this.tabDiv = tabDiv;
		this.resizing = false;
		this.slider = null;
		this.tabsSettingsNode = document.createElement("div");
		this.tabsSettingsNode.setAttribute("class", "tabsSettings");
		this.tabsSettingsNode.className = "tabsSettings";
		this.tabDiv.appendChild(this.tabsSettingsNode);
		this.columnsContainer = document.createElement("div");
		this.columnsContainer.setAttribute("class", "colonnes");
		this.columnsContainer.className = "colonnes";
		this.tabDiv.appendChild(this.columnsContainer);
		this.tabContainers = new Array();
		this.newTabSizes = null;			// The tab sizes during the resize
		for (var i = 0 ; i < this.tabSizes.length ; i++) {
			var divNode = document.createElement("div");
			divNode.setAttribute("id", "container_" + containerID);
			divNode.setAttribute("class", "colonne");
			divNode.className = "colonne";
			// Warning: the columns can't use 100% of the available size : they have a border...
			divNode.style.width = this.tabSizes[i] + '%';
			this.columnsContainer.appendChild(divNode);
			this.tabContainers.push(divNode);
			containerID++;
		}
		if (settings) {
			for (var i = 0 ; i < this.tabSizes.length ; i++) {
				var appList = settings["containers"][i];
				for (var j = 0 ; j < appList.length ; j++) {
					var splits = appList[j].split("_");
					if (splits.length != 2) {
						alert("C'est quoi cette appli de merde avec un _ dans le nom ?");
					} else {
						var appName = splits[0];
						var appId = splits[1];
						this.karibou.instanciateApplication(appName, this.tabContainers[i], appId);
					}
				}
			}
		}
		this.rebuildContainers();
	},
	rebuildContainers: function () {
		for (var i = 0 ; i < this.tabContainers.length ; i++) {
			KSortable.create(this.tabContainers[i], {dropOnEmpty: true, 
								tag: "div", 
								overlap: "horizontal", 
								handle: "handle", 
								constraint: false, 
								containment: this.tabContainers, 
								accept: ["appRootContainer"],
								karibou: this.karibou,
								onUpdate: function(container) {
									Sortable.sortables[container.id].karibou.unlock();
									Sortable.sortables[container.id].karibou.save();
								}});
			Sortable.sortables[this.tabContainers[i].id].karibou = this.karibou;
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
		if (this.tabSizes.length == 1)
			return;
		this.newTabSizes = this.tabSizes;
		this.resizing = true;
		this.slider = document.createElement("div");
		this.slider.setAttribute("class", "slider");
		this.slider.className = "slider";
		var currentWidth = 0;
		var handles = new Array();
		var sliderValues = new Array();
		for (var i = 0 ; i < this.tabContainers.length - 1 ; i++) {
			var widthTxt = new String(this.tabContainers[i].style.width);
			currentWidth += Number(widthTxt.substring(0, widthTxt.length - 1));
			var handle = document.createElement("div");
			handle.setAttribute("class", "handle");
			handle.className = "handle";
			sliderValues.push(currentWidth);
			this.slider.appendChild(handle);
			handles.push(handle);
		}
		this.tabsSettingsNode.appendChild(this.slider);
		var sliderObject = new Control.Slider(handles, this.slider, { range: $R(0, 100), tabObject: this, sliderValue: sliderValues, restricted: true, onSlide: function (value) {
			this.tabObject.newTabSizes = new Array();
			var previousSize = 0;
			for (var i = 0 ; i < value.length ; i++) {
				if ((i == value.length-1) && value[i] > 90)
					value[i] = 90;
				var colSize = value[i] - previousSize;
				if (colSize < 10)
					colSize = 10;
				this.tabObject.tabContainers[i].style.width = colSize + '%';
				this.tabObject.newTabSizes.push(colSize);
				previousSize += colSize;
			}
			this.tabObject.tabContainers[i].style.width = (98.5 - previousSize) + '%';
			this.tabObject.newTabSizes.push(98.5 - previousSize);
		}});
	},
	cancelResize: function () {
		if (!this.resizing) {
			alert("You must be resizing to cancel resize...");
			return;
		}
		for (var i = 0 ; i < this.tabSizes.length ; i++) {
			this.tabContainers[i].style.width = this.tabSizes[i] + '%';
		}
		this.newTabSizes = this.tabSizes;
		this.resizing = false;
		this.slider.parentNode.removeChild(this.slider);
		this.slider = null;
	},
	doneResize: function () {
		if (!this.resizing) {
			alert("You must be resizing to finish resize...");
			return;
		}
		this.tabSizes = this.newTabSizes;
		this.resizing = false;
		this.slider.parentNode.removeChild(this.slider);
		this.slider = null;
		this.karibou.save();
	},
	toJSON: function () {
		var containers = new Array();
		for (var i = 0 ; i < this.tabContainers.length ; i++) {
			var containerNode = this.tabContainers[i];
			var container = new Array();
			for (var j = 0 ; j < containerNode.childNodes.length ; j++) {
				var node = containerNode.childNodes[j];
				if (node.attributes.getNamedItem("kapp")) {
					if (node.attributes.getNamedItem("kapp").nodeValue == "true") {
						var appObj = this.karibou.getAppFromNode(node);
						if (appObj) {
							if (appObj.shaded)
								container.push("*" + node.id);
							else
								container.push(node.id);
						} else
							container.push(node.id);
					}
				}
			}
			containers.push(container);
		}
		return Object.toJSON({"name": this.tabName, "sizes": this.tabSizes, "containers": containers});
	},
	addColumn: function () {
		if (this.resizing)
			return;
		var coef = this.tabSizes.length / (this.tabSizes.length + 1);
		var newSize = 98.5;
		for (var i = 0 ; i < this.tabSizes.length ; i++) {
			this.tabSizes[i] = this.tabSizes[i] * coef;
			newSize = newSize - this.tabSizes[i];
		}
		this.tabSizes[this.tabSizes.length] = Math.floor(newSize);

		var divNode = document.createElement("div");
		divNode.setAttribute("id", "container_" + containerID);
		divNode.setAttribute("class", "colonne");
		divNode.className = "colonne";
		// Warning: the columns can't use 100% of the available size : they have a border...
		divNode.style.width = this.tabSizes[this.tabSizes.length] + '%';
		this.columnsContainer.appendChild(divNode);
		this.tabContainers.push(divNode);
		for (var i = 0 ; i < this.tabSizes.length ; i++) {
			this.tabContainers[i].style.width = this.tabSizes[i] + '%';
		}
		containerID++;
		this.rebuildContainers();
		this.karibou.save();
	},
	removeLastColumn: function () {
		if (this.resizing)
			return;
		// Find a way to list and delete every application on the last column
		var containerNode = this.tabContainers[this.tabContainers.length - 1];
		for (var j = 0 ; j < containerNode.childNodes.length ; j++) {
			var node = containerNode.childNodes[j];
			if (node.attributes.getNamedItem("kapp")) {
				if (node.attributes.getNamedItem("kapp").nodeValue == "true") {
					//alert("Destroy application " + node.id);
					containerNode.removeChild(node);
					j--;
				}
			}
		}

		// Resize columns
		var extraSize = this.tabSizes[this.tabSizes.length - 1]/(this.tabSizes.length - 1);
		var newTabSizes = new Array();
		var newContainers = new Array();
		for (var i = 0 ; i < this.tabSizes.length - 1 ; i++) {
			newTabSizes[i] = this.tabSizes[i] + extraSize;
			newContainers[i] = this.tabContainers[i];
		}
		this.tabSizes = newTabSizes;
		this.tabContainers = newContainers;
		containerNode.parentNode.removeChild(containerNode);

		for (var i = 0 ; i < this.tabSizes.length ; i++) {
			this.tabContainers[i].style.width = this.tabSizes[i] + '%';
		}
		this.rebuildContainers();
		this.karibou.save();
	}
});

// Base class for every karibou application
var KApp = Class.create({
	initialize: function (appName, id, mainContainer, karibou) {
		this.karibou = karibou;
		this.mainContainer = mainContainer;
		this.appName = appName;
		this.mainContainer.setAttribute("kapp", true);
		this.appId = this.appName + "_" + id;
		this.mainContainer.setAttribute("id", this.appId); 
		this.appBox = this.getElementById(this.appName);
		this.appHandle = this.getElementById(this.appName + "_handle");
		this.config = null;
		if (this.constructor.configFields) {
			new Ajax.Request(karibou.appGetConfigUrl + this.appId, {method: 'post', app: this, asynchronous: false, onComplete: function(transport) {
				transport.request.options.app.config = transport.responseText.evalJSON();
			}});
		}
		this.shaded = false;
		this.detached = false;
		this.detachedNode = null;
		this.detachedParent = null;
		this.detachedNext = null;
		this.eventsAutoConnect();
	},
	eventsAutoConnect: function () {
		var regExp = /on_(\w*)_(\w*)/i;
		for (var name in this) {
			if (regExp.match(name)) {
				var matches = regExp.exec(name);
				var eventName = matches[2];
				var realObject = this.getElementById(matches[1]);
				if (realObject) {
					realObject.setAttribute("on" + eventName, "return $app(this)." + name + "(" + this[name].argumentNames() + ");");
				}
			}
		}		
	},
	detach: function() {
		if (!this.detached) {
			this.detachedNode = document.createElement("div");
			this.detachedNode.style.position = "fixed";
			this.detachedNode.style.width = "90%";
			this.detachedNode.style.height = "90%";
			this.detachedNode.style.left = "5%";
			this.detachedNode.style.top = "5%";
			this.detachedNode.style.display = "block";
			this.detachedNode.style.zIndex = 9999;
			this.detachedParent = this.mainContainer.parentNode;
			if (this.mainContainer.nextSibling)
				this.detachedNext = this.mainContainer.nextSibling;
			this.detachedNode.appendChild(this.mainContainer.parentNode.removeChild(this.mainContainer));
			document.body.appendChild(this.detachedNode);
			this.detached = true;
		} else {
			this.detached = false;
			if (this.detachedNext)
				this.detachedParent.insertBefore(this.detachedNode.removeChild(this.detachedNode.firstChild), this.detachedNext);
			else
				this.detachedParent.appendChild(this.detachedNode.removeChild(this.detachedNode.firstChild));
			document.body.removeChild(this.detachedNode);
			this.detachedNode = null;
			this.detachedParent = null;
			this.detachedNext = null;
		}
	},
	configure: function() {
		this.beforeOverlay();
		this.submitBox = document.createElement("div");
		this.submitBox.setAttribute("class", "overBox");
		this.submitBox.className = "overBox";
		this.submitBox.appendChild(document.createElement("br"));
		formNode = document.createElement("form");
		formNode.setAttribute("action", this.karibou.appSetConfigUrl);
		this.submitBox.appendChild(formNode);
		var configFields = {};
		for (var fieldID in this.constructor.configFields) {
			configFields[fieldID] = this.constructor.configFields[fieldID];
		}
		if (this.config) {
			for (var fieldID in configFields) {
				if (this.config[fieldID] != undefined)
					configFields[fieldID]["value"] = this.config[fieldID];
			}
		}
		
		var form = new KForm(configFields, 			// The fields list
				formNode, 				// The form node
				{"miniapp": this.appId},		// The extra parameters
				[this, "submittedConfig"],		// The onSubmit callback
				[this, "cancelledOverlay"]		// The onCancelSubmit callback
				);
		form.buildForm();

		this.mainContainer.appendChild(this.submitBox);
		this.submitHeightBackup = this.mainContainer.style.height;
		if (this.submitBox.scrollHeight > this.mainContainer.scrollHeight)
			this.mainContainer.style.height = this.submitBox.scrollHeight + "px";
	},
	shade: function() {
		this.shaded = !this.shaded;
		Effect.toggle(this.appBox, 'slide', { duration: 0.5 });
		this.onShade();
		this.karibou.unlock();
		this.karibou.save();
	},
	close: function() {
		Effect.SwitchOff(this.mainContainer, { afterFinish: function (effect) {
			effect.element.parentNode.removeChild(effect.element);
			this.karibou.unlock();
			this.karibou.save();
		}, karibou: this.karibou});
		this.onClose();
	},
	submittedConfig: function() {
		// This function will call the onConfig function after doing its own cleanups
		if (this.submitBox) {
			new Ajax.Request(this.karibou.appGetConfigUrl + this.appId, {method: 'post', app: this, asynchronous: false, onComplete: function(transport) {
				transport.request.options.app.config = transport.responseText.evalJSON();
			}});
			this.mainContainer.style.height = this.submitHeightBackup;
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
			this.afterOverlay();
			this.onConfig();
		}
	},
	beforeOverlay: function() {
		// Apps should overload this function to do something before an overlay is displayed.
	},
	afterOverlay: function() {
		// Apps should overload this function to do something after an overlay disappears.
	},
	cancelledOverlay: function() {
		if (this.submitBox) {
			this.mainContainer.style.height = this.submitHeightBackup;
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
			this.afterOverlay();
		}
	},
	submittedContent: function() {
		// This function will call the onSubmit function after doing its own cleanups
		if (this.submitBox) {
			this.mainContainer.style.height = this.submitHeightBackup;
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
			this.afterOverlay();
			this.onSubmit();
		}
	},
	submitContent: function() {
		this.beforeOverlay();
		this.submitBox = document.createElement("div");
		this.submitBox.setAttribute("class", "overBox");
		this.submitBox.className = "overBox";
		this.submitBox.appendChild(document.createElement("br"));
		formNode = document.createElement("form");
		formNode.setAttribute("action", this.karibou.appSubmitUrl);
		this.submitBox.appendChild(formNode);
		
		var form = new KForm(this.constructor.submitFields, 	// The fields list
				formNode, 				// The form node
				{"miniapp": this.appName},		// The extra parameters
				[this, "submittedContent"],		// The onSubmit callback
				[this, "cancelledOverlay"]		// The onCancelSubmit callback
				);
		form.buildForm();

		this.mainContainer.appendChild(this.submitBox);
		this.submitHeightBackup = this.mainContainer.style.height;
		if (this.submitBox.scrollHeight > this.mainContainer.scrollHeight)
			this.mainContainer.style.height = this.submitBox.scrollHeight + "px";
	},
	refresh: function() {
		req = new Ajax.Updater(this.mainContainer, this.karibou.appContentUrl + this.appId, {asynchronous: true, app: this, onComplete: function (transport) {
			app = transport.request.options.app;
			app.appBox = app.getElementById(app.appName);
			app.appHandle = app.getElementById(app.appName + "_handle");
			app.onRefresh();
			app.karibou.getTabFromApplication(app).rebuildContainers();
			app.eventsAutoConnect();
		}});
	},
	getElementById: function (id) {
		return getSubElementById(id, this.mainContainer);
	},
	setTitle: function (title) {
		this.appHandle.innerHTML = title;
	},
	onShade: function() {
		// Apps should overload this if they want to do something after they have been shaded
	},
	onClose: function() {
		// Apps should overload this if they want to do something when they have been closed
	},
	onRefresh: function() {
		// Apps should overload this if they want to do something when they have been refreshed.
	},
	onSubmit: function() {
		// Apps should overload this if they want to do something after content has been submitted
		this.refresh();
	},
	onConfig: function() {
		// Apps should overload this if they want to do something after they have been configured
		this.refresh();
	}
});

// Class responsible for loading the KApp classes, handling the various loaded applications on the screen...
var Karibou = Class.create({
	initialize: function(appContentUrl, appJSUrl, appSubmitUrl, appSetConfigUrl, appGetConfigUrl, saveHomeUrl, tabLinkClicked) {
		this.appSubmitUrl = appSubmitUrl;
		this.appContentUrl = appContentUrl;
		this.appSetConfigUrl = appSetConfigUrl;
		this.appGetConfigUrl = appGetConfigUrl;
		this.saveHomeUrl = saveHomeUrl;
		this.appJSUrl = appJSUrl;
		this.locked = true;
		this.applicationsClass = {};						// {app name => JS class}
		this.loadedApplicationsJS = {};						// [app name]
		this.appLoaders = new Array();						// [KAppLoader], the applications being loaded
		this.appObjs = new Array();						// [KApp objects]
		this.tabsContainer = document.getElementById("tabsContainer");
		this.tabsBar = document.getElementById("tabsBar");
		this.tabLinkClickedCallback = tabLinkClicked;
		this.tabs = {}; 						// {tab name => KTab object}
		this.currentTab = null;
		this.appIds = {};						// {app name => max used ID}
		this.saveTimeout = null;
	},
	getNewIDForApp: function(appName) {
		if (this.appIds[appName] != undefined) {
			this.appIds[appName] = this.appIds[appName] + 1;
		} else {
			this.appIds[appName] = 0;
		}
		return this.appIds[appName];
	},
	createNewTab: function(tabName, settings) {
		if (this.tabs[tabName]) {
			alert("Duplicate tab name");
			return;
		}
		if (this.currentTab != null) {
			this.currentTab.tabDiv.style.display = "none";
			this.currentTab = null;
			for (var i = 0 ; i < this.tabsBar.childNodes.length ; i++) {
				if (this.tabsBar.childNodes[i].attributes.getNamedItem("class") != null) {
					if (this.tabsBar.childNodes[i].attributes.getNamedItem("class").nodeValue == "activeTabLink")
						this.tabsBar.childNodes[i].setAttribute("class", "");
						this.tabsBar.childNodes[i].className = "";
				}
			}
		}
		var tabTitleNode = document.createElement("span");
		var aNode = document.createElement("a");
		$(aNode).observe('click', this.tabLinkClickedCallback);
		tabTitleNode.setAttribute("tabName", tabName);
		tabTitleNode.setAttribute("class", "activeTabLink");
		tabTitleNode.className = "activeTabLink";
		aNode.innerHTML = tabName;
		tabTitleNode.appendChild(aNode);
		this.tabsBar.appendChild(tabTitleNode);

		var tabNode = document.createElement("div");
		tabNode.setAttribute("class", "tab home");
		tabNode.className = "tab home";
		this.tabsContainer.appendChild(tabNode);
		var tabObj = null;
		if (settings) {
			// We are loading a tab from the configuration, do something more... 'exceptionnal'
			tabObj = new KTab(this, null, tabNode, settings);
		} else {
			tabObj = new KTab(this, tabName, tabNode);
		}
		this.tabs[tabName] = tabObj;
		this.currentTab = tabObj;
		this.save();
	},
	tabLinkClicked: function (evt) {
		var elem = Event.element(evt);
		var tab = this.tabs[elem.innerHTML];
		if (tab)
			return this.focusTab(tab);
		return false;
	},
	closeCurrentTab: function () {
		var tabCount = 0;
		for (var tabName in this.tabs)
			tabCount++;
		if (tabCount == 1) {
			if (!confirm("Do you really want to close the last tab ?"))
				return;
		}
		if (this.currentTab != null) {
			if (!confirm("Are you sure ?"))
				return;
			var tabName = this.currentTab.tabName;

			this.currentTab.destroy();

			var newTabs = {};
			var newTabName = null;
			var previousTabName = null;
			for (var oldTabName in this.tabs) {
				if (oldTabName != tabName) {
					newTabs[oldTabName] = this.tabs[oldTabName];
					if (newTabName == null)
						newTabName = newTabName;
					previousTabName = oldTabName;
				} else {
					newTabName = previousTabName;
				}
			}
			
			this.tabs = newTabs;

			newTab = this.tabs[newTabName];
			
			this.currentTab.tabDiv.parentNode.removeChild(this.currentTab.tabDiv);

			for (var i = 0 ; i < this.tabsBar.childNodes.length ; i++) {
				if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabname") != null) {
					if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabname").nodeValue == tabName)
						this.tabsBar.childNodes[i].parentNode.removeChild(this.tabsBar.childNodes[i]);
				}
			}
			if (newTab) {
				this.focusTab(newTab);
			}
		}
		this.save();
	},
	focusTab: function(tabObject) {
		if (this.currentTab != null)
			this.currentTab.tabDiv.style.display="none";
		tabObject.tabDiv.style.display="block";
		this.currentTab = tabObject;
		for (var i = 0 ; i < this.tabsBar.childNodes.length ; i++) {
			if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabname") != null) {
				if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabname").nodeValue != tabObject.tabName)
					this.tabsBar.childNodes[i].setAttribute("class", "");
				else
					this.tabsBar.childNodes[i].setAttribute("class", "activeTabLink");
			}	
		}
		this.save();
	},
	getTabFromApplication: function(application) {
		node = application.mainContainer;
		while ((node.attributes.getNamedItem("class") == null) || (node.attributes.getNamedItem("class").nodeValue != "tab home")) {
			node = node.parentNode;
		}
		for (var name in this.tabs) {
			if (this.tabs[name].tabDiv == node)
				return this.tabs[name];
		}
		return null;
	},
	loadApplicationView: function(application, appId, container) {
		new Ajax.Updater(container, this.appContentUrl + application + "_" + appId, {asynchronous: true, karibou: this, appName: application, appId: appId, onComplete: function (transport) {
			transport.request.options.karibou.applicationViewLoaded(transport.request.options.appName, transport.request.options.appId);
		}});
	},
	applicationViewLoaded: function (appName, appId) {
		for (var i = 0 ; i < this.appLoaders.length ; i++) {
			appLoader = this.appLoaders[i];
			appLoader.handlerMain(appName, appId);
			if (appLoader.loaded()) {
				this.appLoaders = this.appLoaders.without(appLoader);
				i = -1;
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
				i = -1;
			}
		}
	},
	registerApplicationClass: function (applicationName, applicationClass) {
		this.applicationsClass[applicationName] = applicationClass;
		this.applicationJSLoaded(applicationName);
	},
	getApplicationClass: function (applicationName) {
		if (this.applicationsClass[applicationName])
			return this.applicationsClass[applicationName];
		return Class.create(KApp, {});
	},
	instanciateApplication: function (applicationName, container, force_id) {
		var choosenContainer = container;
		if ((this.currentTab) && (!choosenContainer)) {
			var minHeight = this.currentTab.tabContainers[0].scrollHeight + 1;
			for (var i = 0 ; i < this.currentTab.tabContainers.length ; i++) {
				var container = this.currentTab.tabContainers[i];
				if (container.scrollHeight < minHeight) {
					choosenContainer = container;
					minHeight = container.scrollHeight;
				}
			}
		}
		if (choosenContainer == null) {
			alert("Cannot instanciate application, no container.");
			return;
		}
		var appLoader = null;
		if (force_id != null)
			appLoader = new KAppLoader(applicationName, force_id, choosenContainer, this);
		else
			appLoader = new KAppLoader(applicationName, this.getNewIDForApp(applicationName), choosenContainer, this);
		this.appLoaders.push(appLoader);
		appLoader.load();
	},
	registerAppObj: function (app) {
		this.appObjs.push(app);
	},
	getAppFromNode: function (node) {
		if (node == document.body) {
			alert("Went to the body, bad !");
			return;
		}
		if (node.attributes.getNamedItem("kapp")) {
			for (var i = 0 ; i < this.appObjs.length ; i++) {
				if (this.appObjs[i].mainContainer == node)
					return this.appObjs[i];
			}
			//alert("Didn't find the application in this.appObjs");
			return;
		} else if (node.parentNode)
			return this.getAppFromNode(node.parentNode);
	},
	unlock: function () {
		this.locked = false;
	},
	save: function () {
		if (this.locked == true)
			return;
		if (this.saveTimeout != null)
			window.clearTimeout(this.saveTimeout);
		this.saveTimeout = window.setTimeout(function(kar) { kar._realSave(); }, 2000, this);
	},
	_realSave: function () {
		this.saveTimeout = null;
		var data = {"tabs": this.tabs, "appIds": this.appIds};
		if (this.currentTab)
			data["defaultTab"] = this.currentTab.tabName;
		var jsonised = Object.toJSON(data);
		new Ajax.Request(this.saveHomeUrl, {method: 'post', postBody: "home=" + encodeURIComponent(jsonised)});
	},
	loadUrl: function (url) {
		new Ajax.Request(url, {method: 'get', karibou: this, onComplete: function(transport) {
			transport.request.options.karibou.loadData(transport.responseText.evalJSON());
		}});
	},
	loadData: function (data) {
		var tabs = data["tabs"];
		for (var tabName in tabs) {
			this.createNewTab(tabName, tabs[tabName]);
		}
		this.appIds = data["appIds"];
		if ("defaultTab" in data) {
			this.focusTab(this.tabs[data["defaultTab"]]);
		}
	}
});

var KAppLoader = Class.create({
	initialize: function(applicationName, id, container, karibou) {
		this.jsloaded = false;
		this.mainloaded = false;
		this.done = false;
		if (applicationName.charAt(0) == '*') {
			this.shaded = true;
			this.appName = applicationName.substr(1);
		} else {
			this.shaded = false;
			this.appName = applicationName;
		}
		this.appId = id;
		this.container = container;
		this.karibou = karibou;
		this.targetNode = document.createElement("div");
		this.targetNode.setAttribute("class", "appRootContainer");
		this.targetNode.className = "appRootContainer";
		this.targetNode.innerHTML = "Please wait...";
		this.container.appendChild(this.targetNode);
	},
	load: function () {
		this.karibou.loadApplicationJS(this.appName);
		this.karibou.loadApplicationView(this.appName, this.appId, this.targetNode);
	},
	loaded: function () {
		return this.jsloaded && this.mainloaded;
	},
	onHandler: function () {
		if (this.loaded()) {
			if (this.done)
				return;
			this.done = true;
			try {
				var appObj = new (this.karibou.getApplicationClass(this.appName))(this.appName, this.appId, this.targetNode, this.karibou);
				this.karibou.registerAppObj(appObj);
				if (this.shaded)
					appObj.shade();
				if (this.karibou.currentTab != null) 
					this.karibou.currentTab.rebuildContainers();
				this.karibou.save();
			} catch (err) {
				alert("Exception occured with " + this.appName + ":" + this.appId);
				alert(err);
			}
		}
	},
	handlerJS: function (application) {
		if (this.appName == application)
			this.jsloaded = true;
		this.onHandler();
	},
	handlerMain: function (appName, appId) {
		if ((this.appName == appName) && (this.appId == appId))
			this.mainloaded = true;
		this.onHandler();
	}
});

Karibou.APP_LOADING = 0;
Karibou.APP_LOADED = 1;
Karibou.APP_FAILURE = 2;

