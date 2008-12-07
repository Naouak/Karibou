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
		this.onShade();
	},
	onShade: function() {
		// Apps should overload this if they want to do something after they have been shaded
	},
	onSubmit: function() {
		// Apps should overload this if they want to do something after content has been submitted
		this.refresh();
	},
	doneSubmitContent: function() {
		// Check the fields in the form
		// Warn if there is something invalid
		// Then send the content
		// Ho, BTW, use some nice hacks to submit files... http://www.openjs.com/articles/ajax/ajax_file_upload/ and http://www.openjs.com/articles/ajax/ajax_file_upload/response_data.php
		if (!this.submitBox)
			return false;

		var queryComponents = new Array();

		queryComponents.push("miniapp=" + encodeURIComponent(this.appName));
		var containsFile = false;

		for (fieldID in this.constructor.submitFields) {
			fieldObject = this.constructor.submitFields[fieldID];
			if (fieldObject["type"] == "span")
				continue;
			formObject = getSubElementById(fieldID, this.submitBox);
			if (fieldObject["type"] == "text") {
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
				queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
			} else if (fieldObject["type"] == "textarea") {
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
				queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
			} else if (fieldObject["type"] == "file") {
				alert("Aie aie sir");
				containsFile = true;
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
			} else {
				alert("Hooo no, I can't do this !");
				return false;
			}
		}
		if (containsFile) {
			iframeName = "iframe_" + (new Date()).getTime();
			var formNode = getSubElementById("submitForm", this.submitBox);	
			iframeNode = document.createElement("iframe");
			iframeNode.setAttribute("src", "");
			iframeNode.setAttribute("name", iframeName);
			iframeNode.setAttribute("id", iframeName);
			//Debug : iframeNode.setAttribute("style", "border: 1px solid rgb(204, 204, 204); width: 100px; height: 100px;");
			iframeNode.style.display = "none";
			formNode.appendChild(iframeNode);
			formNode.setAttribute("target", iframeName);
			iframeNode.onload = function () { app = $app(this); app.cancelSubmit(); app.onSubmit(); return true; };
			return true; 
		} else {
			var postData = queryComponents.join('&');
			new Ajax.Request(this.karibou.appSubmitUrl, {asynchronous: true, evalScripts: false, method: 'post', postBody: postData});
			this.onSubmit();
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
			this.mainContainer.style.height = this.submitHeightBackup;
			return false;
		}
	},
	cancelSubmit: function() {
		if (this.submitBox) {
			this.mainContainer.style.height = this.submitHeightBackup;
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
		}
	},
	submitContent: function() {
		//alert(this.constructor.submitFields);
		this.submitBox = document.createElement("div");
		this.submitBox.setAttribute("class", "overBox");
		this.submitBox.appendChild(document.createElement("br"));
		formNode = document.createElement("form");
		formNode.setAttribute("id", "submitForm");
		this.submitBox.appendChild(formNode);
		formNode.setAttribute("onsubmit", "return $app(this).doneSubmitContent();");
		hiddenNode = document.createElement("input");
		hiddenNode.setAttribute("type", "hidden");
		hiddenNode.setAttribute("name", "miniapp");
		hiddenNode.setAttribute("id", "miniapp");
		hiddenNode.setAttribute("value", this.appName);
		formNode.appendChild(hiddenNode);
		formNode.setAttribute("action", this.karibou.appSubmitUrl);
		formNode.setAttribute("method", "post");
		for (fieldID in this.constructor.submitFields) {
			fieldObject = this.constructor.submitFields[fieldID];
			if (fieldObject["type"] == "span") {
				spanNode = document.createElement("span");
				spanNode.innerHTML = fieldObject["text"];
				formNode.appendChild(spanNode);
			} else if (fieldObject["type"] == "text") {
				if (fieldObject["label"]) {
					lblNode = document.createElement("label");
					lblNode.innerHTML = fieldObject["label"];
					lblNode.setAttribute("for", fieldID);
					formNode.appendChild(lblNode);
				}
				inputNode = document.createElement("input");
				inputNode.setAttribute("id", fieldID);
				inputNode.setAttribute("name", fieldID);
				inputNode.setAttribute("type", "text");
				if (fieldObject["maxlength"])
					inputNode.setAttribute("maxlength", fieldObject["maxlength"]);
				formNode.appendChild(inputNode);
			} else if (fieldObject["type"] == "textarea") {
				if (fieldObject["label"]) {
					lblNode = document.createElement("label");
					lblNode.innerHTML = fieldObject["label"];
					lblNode.setAttribute("for", fieldID);
					formNode.appendChild(lblNode);
					formNode.appendChild(document.createElement("br"));
				}
				areaNode = document.createElement("textarea");
				areaNode.setAttribute("id", fieldID);
				areaNode.setAttribute("name", fieldID);
				if (fieldObject["columns"])
					areaNode.setAttribute("cols", fieldObject["columns"]);
				if (fieldObject["rows"])
					areaNode.setAttribute("rows", fieldObject["rows"]);
				formNode.appendChild(areaNode);
			} else if (fieldObject["type"] == "file") {
				formNode.setAttribute("enctype", "multipart/form-data");
				if (fieldObject["label"]) {
					lblNode = document.createElement("label");
					lblNode.innerHTML = fieldObject["label"];
					lblNode.setAttribute("for", fieldID);
					formNode.appendChild(lblNode);
					formNode.appendChild(document.createElement("br"));
				}
				fileNode = document.createElement("input");
				fileNode.setAttribute("id", fieldID);
				fileNode.setAttribute("name", fieldID);
				fileNode.setAttribute("type", "file");
				formNode.appendChild(fileNode);
			} else {
				alert("Unknown field type " + fieldObject["type"]);
			}
			formNode.appendChild(document.createElement("br"));
		}
		submitNode = document.createElement("input");
		submitNode.setAttribute("type", "submit");
		submitNode.setAttribute("value", "Envoyer");
		formNode.appendChild(submitNode);
		cancelNode = document.createElement("input");
		cancelNode.setAttribute("type", "button");
		cancelNode.setAttribute("value", "Cancel");
		cancelNode.setAttribute("onclick", "$app(this).cancelSubmit()");
		formNode.appendChild(cancelNode);
		this.mainContainer.appendChild(this.submitBox);
		this.submitHeightBackup = this.mainContainer.style.height;
		if (this.submitBox.scrollHeight > this.mainContainer.scrollHeight)
			this.mainContainer.style.height = this.submitBox.scrollHeight + "px";
	},
	refresh: function() {
		req = new Ajax.Updater(this.mainContainer, this.karibou.appContentUrl + this.appName, {asynchronous: true, app: this, onComplete: function (transport) {
			app = transport.request.options.app;
			app.appBox = app.getElementById(app.appName);
			app.karibou.getTabFromApplication(app).rebuildContainers();
		}});
	},
	getElementById: function (id) {
		return getSubElementById(id, this.mainContainer);
	},
	setTitle: function (title) {
		this.appHandle.innerHTML = title;
	}
});

// Class responsible for loading the KApp classes, handling the various loaded applications on the screen...
var Karibou = Class.create({
	initialize: function(appListingUrl, appContentUrl, appJSUrl, appSubmitUrl, tabLinkClicked) {
		this.appListingUrl = appListingUrl;
		this.appSubmitUrl = appSubmitUrl;
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
			if (((newCurrentTabIdx == -1) && (this.tabs.length > 0)) || (newCurrentTabIdx >= this.tabs.length))
				newCurrentTabIdx = 0;
			
			this.currentTab.tabDiv.parentNode.removeChild(this.currentTab.tabDiv);

			for (var i = 0 ; i < this.tabsBar.childNodes.length ; i++) {
				if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabname") != null) {
					if (this.tabsBar.childNodes[i].attributes.getNamedItem("tabname").nodeValue == tabName)
						this.tabsBar.childNodes[i].parentNode.removeChild(this.tabsBar.childNodes[i]);
				}	
			}
			if (newCurrentTabIdx != -1) {
				this.focusTab(this.tabs[newCurrentTabIdx]);
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
	getTabFromApplication: function(application) {
		node = application.mainContainer;
		while ((node.id == null) || (node.id.substring(0, 4) != "tab_"))
			node = node.parentNode;
		tabId = node.id.substring(4);
		return this.tabs[tabId];
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
