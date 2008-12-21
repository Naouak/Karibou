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
	// Special case : if settings is not undefined, we ignore tabName, and we will require many applications....
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
		this.tabDiv.appendChild(this.tabsSettingsNode);
		this.columnsContainer = document.createElement("div");
		this.columnsContainer.setAttribute("class", "colonnes");
		this.tabDiv.appendChild(this.columnsContainer);
		this.tabContainers = new Array();
		this.newTabSizes = null;			// The tab sizes during the resize
		for (var i = 0 ; i < this.tabSizes.length ; i++) {
			var divNode = document.createElement("div");
			divNode.setAttribute("id", "container_" + containerID);
			divNode.setAttribute("class", "colonne");
			// Warning: the columns can't use 100% of the available size : they have a border...
			divNode.style.width = this.tabSizes[i] + '%';
			this.columnsContainer.appendChild(divNode);
			this.tabContainers.push(divNode);
			containerID++;
		}
		this.rebuildContainers();
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
								karibou: this,
								onUpdate: function(container) {
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
		this.newTabSizes = this.tabSizes;
		this.resizing = true;
		this.slider = document.createElement("div");
		this.slider.setAttribute("class", "slider");
		var currentWidth = 0;
		var handles = new Array();
		var sliderValues = new Array();
		for (var i = 0 ; i < this.tabContainers.length - 1 ; i++) {
			var widthTxt = new String(this.tabContainers[i].style.width);
			currentWidth += Number(widthTxt.substring(0, widthTxt.length - 1));
			var handle = document.createElement("div");
			handle.setAttribute("class", "handle");
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
			this.tabObject.tabContainers[i].style.width = (99 - previousSize) + '%';
			this.tabObject.newTabSizes.push(99 - previousSize);
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
						container.push(node.id);
					}
				}
			}
			containers.push(container);
		}
		return Object.toJSON({"name": this.tabName, "sizes": this.tabSizes, "containers": containers});
	}
});

// Class to handle javascript-validated and sent forms
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
			} else if ( fieldObject["type"] == "int" || fieldObject["type"] == "float") {
				if (fieldObject["label"]) {
					lblNode = document.createElement("label");
					lblNode.innerHTML = fieldObject["label"];
					lblNode.setAttribute("for", fieldId);
					fromNode.appendChild(lblNode);
				}
				inputNode = document.createElement("input");
				inputNode.setAttribute("id", fieldID);
				inputNode.setAttribute("name", fieldID);
				inputNode.setAttribute("type", "text");
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
			}else if (fieldObject["type"] == "int") {
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
				if (formObject.value !=""){
					var num = Number(formObject.value);
					if (num.toString() == "NaN" || Math.round(num) == num){
						alert("Field value is not a valid number, you must enter a int");
						formObject.focus();
						return false;
					}
				}
				if ((formObject.value < fieldObject["min"]) || (formObject.value > fieldObject["max"])){
					alert("number is not between" + fieldObject["max"] + "and" + fieldObject["min"]);
					formObject.focus();
					return false;
				}
				queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
			} else if (fieldObject["type"] == "float") {
				if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
					if (formObject.value == "") {
						alert("One or more fields are missing.");
						formObject.focus();
						return false;
					}
				}
				if (formObject.value !=""){
					var num = Number(formObject.value);
					if (num.toString() == "NaN" ){
						alert("Field value is not a valid number");
						formObject.focus();
						return false;
					}
				}
				if ((formObject.value < fieldObject["min"]) || (formObject.value > fieldObject["max"])){
					alert("number is not between" + fieldObject["max"] + "and" + fieldObject["min"]);
					formObject.focus();
					return false;
				}
				queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
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
			new Ajax.Request(karibou.appGetConfigUrl + this.appId, {method: 'post', app: this, asynchronous: false, onComplete: function(transport) {
				transport.request.options.app.config = transport.responseText.evalJSON();
			}});
		}
		this.karibou.save();
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
		Effect.toggle(this.appBox, 'slide', { duration: 0.5 });
		this.onShade();
	},
	onShade: function() {
		// Apps should overload this if they want to do something after they have been shaded
	},
	close: function() {
		this.mainContainer.parentNode.removeChild(this.mainContainer);
		this.onClose();
		this.karibou.save();
	},
	onClose: function() {
		// Apps should overload this if they want to do something when they have been closed
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
			new Ajax.Request(this.karibou.appGetConfigUrl + this.appId, {method: 'post', app: this, asynchronous: false, onComplete: function(transport) {
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
	initialize: function(appContentUrl, appJSUrl, appSubmitUrl, appSetConfigUrl, appGetConfigUrl, saveHomeUrl, tabLinkClicked) {
		this.appSubmitUrl = appSubmitUrl;
		this.appContentUrl = appContentUrl;
		this.appSetConfigUrl = appSetConfigUrl;
		this.appGetConfigUrl = appGetConfigUrl;
		this.saveHomeUrl = saveHomeUrl;
		this.appJSUrl = appJSUrl;
		this.applicationsClass = new Array();					// {app name => JS class}
		this.loadedApplicationsJS = new Array();				// [app name]
		this.appLoaders = new Array();						// [KAppLoader], the applications being loaded
		this.appObjs = new Array();						// [KApp objects]
		this.tabsContainer = document.getElementById("tabsContainer");
		this.tabsBar = document.getElementById("tabsBar");
		this.tabLinkClickedCallback = tabLinkClicked;
		this.tabs = {}; 		// {tab name => KTab object} //new Array();						// [KTab objects]
		this.currentTab = null;
		this.appIds = {};						// {app name => max used ID}
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
				}
			}
		}
		var tabTitleNode = document.createElement("span");
		var aNode = document.createElement("a");
		$(aNode).observe('click', this.tabLinkClickedCallback);
		tabTitleNode.setAttribute("tabName", tabName);
		tabTitleNode.setAttribute("class", "activeTabLink");
		aNode.innerHTML = tabName;
		tabTitleNode.appendChild(aNode);
		this.tabsBar.appendChild(tabTitleNode);

		var tabNode = document.createElement("div");
		tabNode.setAttribute("class", "tab home");
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
		if (this.currentTab != null) {
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
			this.save();
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
		new Ajax.Updater(container, this.appContentUrl + application + "_" + appId, {asynchronous: true, karibou: this, app: application, onComplete: function (transport) {
			transport.request.options.karibou.applicationViewLoaded(transport.request.options.app);
		}});
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
			var appLoader = new KAppLoader(applicationName, force_id, choosenContainer, this);
		else
			var appLoader = new KAppLoader(applicationName, this.getNewIDForApp(applicationName), choosenContainer, this);
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
	},
	save: function (node) {
		var data = {"tabs": this.tabs, "appIds": this.appIds};
		var jsonised = Object.toJSON(data);
		document.getElementById("configViewer").innerHTML = jsonised;
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
			klass = this.karibou.getApplicationClass(this.appName);
			var appObj = new klass(this.appName, this.appId, this.targetNode, this.karibou);
			this.karibou.registerAppObj(appObj);
			if (this.karibou.currentTab != null) 
				this.karibou.currentTab.rebuildContainers();
		}
	},
	handlerJS: function (application) {
		//alert("handleJS called");
		if (this.appName == application)
			this.jsloaded = true;
		this.onHandler();
	},
	handlerMain: function (application) {
		//alert("handlerMain called");
		if (this.appName == application)
			this.mainloaded = true;
		this.onHandler();
	}
});

Karibou.APP_LOADING = 0;
Karibou.APP_LOADED = 1;
Karibou.APP_FAILURE = 2;

