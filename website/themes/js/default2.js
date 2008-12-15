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

KForm = Class.create({
	initialize: function (formFields, targetNode, extraParameters, submitCallBack, cancelCallBack) {
		if (!KForm.forms) {
			KForm.forms = new Array();
			KForm.getFormFromNode = this.getFormFromNode;
		}
		KForm.forms.push(new Array(targetNode, this));
		this.extraParameters = extraParameters;		// {name => value}
		this.formFields = formFields;
		this.targetNode = targetNode;
		this.submitCallBack = submitCallBack;
		this.cancelCallBack = cancelCallBack;
	},
	buildForm: function() {
		var formNode = this.targetNode;
		formNode.setAttribute("method", "post");
		formNode.setAttribute("onsubmit", "return KForm.getFormFromNode(this).submit();");

		for (fieldID in this.formFields) {
			fieldObject = this.formFields[fieldID];
			if (fieldObject["type"] == "span") {
				spanNode = document.createElement("span");
				spanNode.innerHTML = fieldObject["text"];
				formNode.appendChild(spanNode);
			} else if ((fieldObject["type"] == "text") || (fieldObject["type"] == "url")) {
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
				if (fieldObject["value"])
					inputNode.setAttribute("value", fieldObject["value"]);
				formNode.appendChild(inputNode);
			} else if (fieldObject["type"] == "date") {
				if (fieldObject["label"]) {
					lblNode = document.createElement("label");
					lblNode.setAttribute("for", fieldID);
					acroNode = document.createElement("acronym");
					acroNode.setAttribute("title", "Format : dd/mm/yyyy");
					acroNode.innerHTML = fieldObject["label"];
					lblNode.appendChild(acroNode);
					formNode.appendChild(lblNode);
				}
				inputNode = document.createElement("input");
				inputNode.setAttribute("id", fieldID);
				inputNode.setAttribute("name", fieldID);
				inputNode.setAttribute("type", "text");
				if (fieldObject["value"])
					inputNode.setAttribute("value", fieldObject["value"]);
				if (fieldObject["maxlength"])
					inputNode.setAttribute("maxlength", fieldObject["maxlength"]);
				formNode.appendChild(inputNode);
				calNode = document.createElement("span");
				calNode.setAttribute("class", "calendar_link");
				calNode.setAttribute("for", fieldID);
				calNode.onclick = function() {
					var inputNode = $app(this).getElementById(this.attributes.getNamedItem("for").nodeValue);
					var divNode = document.createElement("div");
					divNode.setAttribute("class", "floating_calendar");
					inputNode.parentNode.insertBefore(divNode, inputNode.nextSibling);
					var inputScal = new scal(divNode, inputNode, {updateformat: 'dd/mm/yyyy'});
				};
				txtNode = document.createElement("span");
				txtNode.setAttribute("class", "text");
				txtNode.innerHTML = "Open calendar";
				calNode.appendChild(txtNode);
				formNode.appendChild(calNode);
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
				if (fieldObject["value"])
					areaNode.innerHTML = fieldObject["value"];
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

		if (this.cancelCallBack) {
			cancelNode = document.createElement("input");
			cancelNode.setAttribute("type", "button");
			cancelNode.setAttribute("value", "Cancel");
			cancelNode.setAttribute("onclick", "KForm.getFormFromNode(this.parentNode).cancel();");
			formNode.appendChild(cancelNode);
		}
	},
	getFormFromNode: function (node) {
		for (var idx = 0 ; idx < KForm.forms.length ; idx++) {
			form = KForm.forms[idx];
			if (form[0] == node) {
				return form[1];
			}
		}
		return null;
	},
	cancel: function () {
		if (this.cancelCallBack) {
			if (this.cancelCallBack instanceof Array) {
				this.cancelCallBack[0][this.cancelCallBack[1]]();
			} else if (this.cancelCallBack instanceof Function) {
				this.cancelCallBack();
			}
		}
	},
	submitted: function () {
		// This function is called after content has been sent...
		if (this.submitCallBack instanceof Array) {
			this.submitCallBack[0][this.submitCallBack[1]]();
		} else if (this.submitCallBack instanceof Function) {
			this.submitCallBack();
		}
	},
	submit: function () {
		// Check the fields in the form
		// Warn if there is something invalid
		// Then send the content
		// Ho, BTW, use some nice hacks to submit files... http://www.openjs.com/articles/ajax/ajax_file_upload/ and http://www.openjs.com/articles/ajax/ajax_file_upload/response_data.php
		var queryComponents = new Array();
		for (paramName in this.extraParameters)
			queryComponents.push(paramName + "=" + encodeURIComponent(this.extraParameters[paramName]));
		var containsFile = false;

		for (fieldID in this.formFields) {
			fieldObject = this.formFields[fieldID];
			if (fieldObject["type"] == "span")
				continue;
			formObject = getSubElementById(fieldID, this.targetNode);
			if (fieldObject["type"] == "text") {
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
				queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
			} else if (fieldObject["type"] == "url") {
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
				//@TODO : validate with a regular expression for urls, I'm too lazy to do that now
				queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
			} else if (fieldObject["type"] == "date") {
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
				if (formObject.value != "") {
					// Validate the date with a regular expression
					if (!formObject.value.match(/^\d\d\/\d\d\/\d\d\d\d$/)) {
						alert("Field value is not valid.");
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
			iframeNode = document.createElement("iframe");
			iframeNode.setAttribute("src", "");
			iframeNode.setAttribute("name", iframeName);
			iframeNode.setAttribute("id", iframeName);
			//Debug : iframeNode.setAttribute("style", "border: 1px solid rgb(204, 204, 204); width: 100px; height: 100px; color: white;");
			iframeNode.style.display = "none";
			this.targetNode.appendChild(iframeNode);
			this.targetNode.setAttribute("target", iframeName);
			iframeNode.onload = function () { KForm.getFormFromNode(this.parentNode).submitted(); return true; };
			return true; 
		} else {
			var postData = queryComponents.join('&');
			var url = this.targetNode.attributes.getNamedItem("action").nodeValue;
			new Ajax.Request(url, {asynchronous: true, evalScripts: false, method: 'post', postBody: postData, form: this, onComplete: function(transport) {
				form = transport.request.options.form;
				form.submitted();
			}});
			return false;
		}
		return false;
	}
});

// Base class for every karibou application
KApp = Class.create({
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
			new Ajax.Request(karibou.appGetConfigUrl + this.appId, {method: 'post', app: this, onComplete: function(transport) {
				transport.request.options.app.config = transport.responseText.evalJSON();
			}});
		}
	},
	configure: function() {
		this.submitBox = document.createElement("div");
		this.submitBox.setAttribute("class", "overBox");
		this.submitBox.appendChild(document.createElement("br"));
		formNode = document.createElement("form");
		formNode.setAttribute("action", this.karibou.appSetConfigUrl);
		this.submitBox.appendChild(formNode);

		var configFields = this.constructor.configFields;
		if (this.config) {
			for (var fieldID in configFields) {
				if (this.config[fieldID])
					configFields[fieldID]["value"] = this.config[fieldID];
			}
		}
		
		var form = new KForm(this.constructor.configFields, 	// The fields list
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
	onConfig: function() {
		// Apps should overload this if they want to do something after they have been configured
		this.refresh();
	},
	submittedConfig: function() {
		// This function will call the onConfig function after doing its own cleanups
		if (this.submitBox) {
			new Ajax.Request(this.karibou.appGetConfigUrl + this.appId, {method: 'post', app: this, onComplete: function(transport) {
				transport.request.options.app.config = transport.responseText.evalJSON();
			}});
			this.mainContainer.style.height = this.submitHeightBackup;
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
			this.onConfig();
		}
	},
	cancelledOverlay: function() {
		if (this.submitBox) {
			this.mainContainer.style.height = this.submitHeightBackup;
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
		}
	},
	submittedContent: function() {
		// This function will call the onSubmit function after doing its own cleanups
		if (this.submitBox) {
			this.mainContainer.style.height = this.submitHeightBackup;
			this.submitBox.parentNode.removeChild(this.submitBox);
			this.submitBox = null;
			this.onSubmit();
		}
	},
	submitContent: function() {
		this.submitBox = document.createElement("div");
		this.submitBox.setAttribute("class", "overBox");
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
	initialize: function(appContentUrl, appJSUrl, appSubmitUrl, appSetConfigUrl, appGetConfigUrl, tabLinkClicked) {
		this.appSubmitUrl = appSubmitUrl;
		this.appContentUrl = appContentUrl;
		this.appSetConfigUrl = appSetConfigUrl;
		this.appGetConfigUrl = appGetConfigUrl;
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
	loadApplicationView: function(application, appId, container) {
		req = new Ajax.Updater(container, this.appContentUrl + application + "_" + appId, {asynchronous: true, onComplete: function (transport) {
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
		var appLoader = new KAppLoader(applicationName, this.getNewIDForApp(applicationName), container, this);
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
	initialize: function(applicationName, id, container, karibou) {
		this.jsloaded = false;
		this.mainloaded = false;
		this.appName = applicationName;
		this.appId = id;
		this.container = container;
		this.karibou = karibou;
		this.targetNode = document.createElement("div");
		this.targetNode.setAttribute("class", "appRootContainer");
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
			klass = this.karibou.getApplicationClass(this.appName);
			this.container.appendChild(this.targetNode);
			var appObj = new klass(this.appName, this.appId, this.targetNode, this.karibou);
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
