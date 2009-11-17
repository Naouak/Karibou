/**
 * Class used for generating and managing form
 * @module default2
 * @class KForm
 */
KForm = Class.create({
    /**
     * Called when you create a KForm
     * @method initialize
     * @param {object} formFields supposedly the fields of your form
     * @param {DOMNode} targetNode where in the node you will add it
     * @param {object} extraParameters Extra values (as array) that will be sent together with the other data
     * @param {function array} submitCallback Function or Array of function that will be called on submit
     * @param {function array} cancelCallback Function or Array of function that will be called on cancelling
     */
    initialize: function (formFields, targetNode, extraParameters, submitCallBack, cancelCallBack) {
	if (!KForm.forms) {
	    KForm.forms = new Array();
	    KForm.getFormFromNode = this.getFormFromNode;
	}
	KForm.forms.push(new Array(targetNode, this));
	this.extraParameters = extraParameters;
	this.formFields = formFields;
	this.targetNode = targetNode;
	this.submitCallBack = submitCallBack;
	this.cancelCallBack = cancelCallBack;
    },

    //********************************************FIELDS******************************************

    /**
     * List every type of fields. Useful for adding your own field.
     * @property fieldsType
     */
    fieldsType: {
	/**
     * Span field
     * @property __span
     */
	__span: {
	    build: function(formNode,fieldID,fieldObject){
		var spanNode = document.createElement("span");
		spanNode.innerHTML = fieldObject["text"];
		formNode.appendChild(spanNode);
	    }
	},
	/**
	  * URL field
	  * @property __url
	  */
	__url: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
		    }
		    var inputNode = document.createElement("input");
		    inputNode.setAttribute("id", fieldID);
		    inputNode.setAttribute("name", fieldID);
		    inputNode.setAttribute("type", "text");

		    if (fieldObject["maxlength"])
			inputNode.setAttribute("maxlength", fieldObject["maxlength"]);
		    if (fieldObject["value"])
			inputNode.setAttribute("value", fieldObject["value"]);
		    formNode.appendChild(inputNode);
	    }
	},
	/**
	  * Password field
	  * @property __password
	  */
	__password: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
		    }
		    var inputNode = document.createElement("input");
		    inputNode.setAttribute("id", fieldID);
		    inputNode.setAttribute("name", fieldID);
		    inputNode.setAttribute("type", "password");

		    if (fieldObject["maxlength"])
			inputNode.setAttribute("maxlength", fieldObject["maxlength"]);
		    if (fieldObject["value"])
			inputNode.setAttribute("value", fieldObject["value"]);
		    formNode.appendChild(inputNode);
	    }
	},

   /**
     * Text field
     * @property __text
     */
	__text: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
		    }
		    var inputNode = document.createElement("input");
		    inputNode.setAttribute("id", fieldID);
		    inputNode.setAttribute("name", fieldID);
		    inputNode.setAttribute("type", "text");

		    if (fieldObject["maxlength"])
			inputNode.setAttribute("maxlength", fieldObject["maxlength"]);
		    if (fieldObject["value"])
			inputNode.setAttribute("value", fieldObject["value"]);
		    formNode.appendChild(inputNode);
	    }
	},
	/**
     * Help field
     * @property __help
     */
	__help: {
	    build: function(formNode,fieldID,fieldObject){
		var titleNode = document.createElement("a");
		titleNode.setAttribute("href", "#");
		if (fieldObject["title"])
			titleNode.innerHTML = fieldObject["title"];
		else
			titleNode.innerHTML = "Help ?";
		formNode.appendChild(titleNode);
		var helpNode = document.createElement("span");
		helpNode.setAttribute("style", "display: none;");
		helpNode.innerHTML = fieldObject["text"];
		helpNode.id = "__karibou_help_node_" + Math.ceil(Math.random()*1000000);
		titleNode.setAttribute("onclick", "new Effect.toggle(document.getElementById('" + helpNode.id + "')); return false;");
		formNode.appendChild(helpNode);
		helpNode.insertBefore(document.createElement("br"), helpNode.firstChild);
	    }
	},
	/**
     * Date field
     * @property __date
     */
	__date: {
	    build: function(formNode,fieldID,fieldObject){
		if (fieldObject["label"]) {
		    var lblNode = document.createElement("label");
		    lblNode.setAttribute("for", fieldID);
		    var acroNode = document.createElement("acronym");
		    acroNode.setAttribute("title", "Format : dd/mm/yyyy");
		    acroNode.innerHTML = fieldObject["label"];
		    lblNode.appendChild(acroNode);
		    formNode.appendChild(lblNode);
		}
		var inputNode = document.createElement("input");
		inputNode.setAttribute("id", fieldID);
		inputNode.setAttribute("name", fieldID);
		inputNode.setAttribute("type", "text");
		if (fieldObject["value"])
		    inputNode.setAttribute("value", fieldObject["value"]);
		if (fieldObject["maxlength"])
		    inputNode.setAttribute("maxlength", fieldObject["maxlength"]);
		formNode.appendChild(inputNode);
		var calNode = document.createElement("span");
		calNode.setAttribute("class", "calendar_link");
		calNode.setAttribute("for", fieldID);
		calNode.onclick = function() {
		    var inputNode = $app(this).getElementById(this.attributes.getNamedItem("for").nodeValue);
		    var divNode = document.createElement("div");
		    divNode.setAttribute("class", "floating_calendar");
		    inputNode.parentNode.insertBefore(divNode, inputNode.nextSibling);
		    var callBack = function(d) {
		        inputNode.value = d.format('dd/mm/yyyy');
		        inputScal.closeCalendar();
		        inputScal.destroy();
		        divNode.parentNode.removeChild(divNode);
		    };
		    var inputScal = new scal(divNode, callBack);
		};
		var txtNode = document.createElement("span");
		txtNode.setAttribute("class", "text");
		txtNode.innerHTML = "Open calendar";
		calNode.appendChild(txtNode);
		formNode.appendChild(calNode);
	    }
	},
	/**
     * Textarea field
     * @property __textarea
     */
	__textarea: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
			formNode.appendChild(document.createElement("br"));
		    }
		    var areaNode = document.createElement("textarea");
		    areaNode.setAttribute("id", fieldID);
		    areaNode.setAttribute("name", fieldID);
		    if (fieldObject["columns"])
			areaNode.setAttribute("cols", fieldObject["columns"]);
		    if (fieldObject["rows"])
			areaNode.setAttribute("rows", fieldObject["rows"]);
		    if (fieldObject["value"])
			areaNode.innerHTML = fieldObject["value"];
		    formNode.appendChild(areaNode);
	    }
	},
	/**
     * File field
     * @property __file
     */
	__file: {
	    build: function(formNode,fieldID,fieldObject,form){
		    form.setAttribute("enctype", "multipart/form-data");
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
			formNode.appendChild(document.createElement("br"));
		    }
		    var fileNode = document.createElement("input");
		    fileNode.setAttribute("id", fieldID);
		    fileNode.setAttribute("name", fieldID);
		    fileNode.setAttribute("type", "file");
		    formNode.appendChild(fileNode);
	    }
	},
	/**
     * Float field
     * @property __float
     */
	__float: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
		    }
		    var inputNode = document.createElement("input");
		    inputNode.setAttribute("id", fieldID);
		    inputNode.setAttribute("name", fieldID);
		    inputNode.setAttribute("type", "text");
		    if (fieldObject["value"])
			inputNode.setAttribute("value", fieldObject["value"]);
		    formNode.appendChild(inputNode);
	    }
	},
	/**
	  * Integer field
	  * @Property __int
	  */
	__int: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
		    }
		    var inputNode = document.createElement("input");
		    inputNode.setAttribute("id", fieldID);
		    inputNode.setAttribute("name", fieldID);
		    inputNode.setAttribute("type", "text");
		    if (fieldObject["value"])
			inputNode.setAttribute("value", fieldObject["value"]);
		    formNode.appendChild(inputNode);
	    }
	},
	/**
     * Bool field
     * @property __bool
     */
	__bool: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("label");
			lblNode.innerHTML = fieldObject["label"];
			lblNode.setAttribute("for", fieldID);
			formNode.appendChild(lblNode);
		    }
		    var inputNode = document.createElement("input");
		    inputNode.setAttribute("id", fieldID);
		    inputNode.setAttribute("name", fieldID);
		    inputNode.setAttribute("type", "checkbox");
		    inputNode.setAttribute("value", "on");
		    if (fieldObject["value"]) {
			if ((fieldObject["value"] == "true") || (fieldObject["value"] == 1) || (fieldObject["value"] == true))
			    inputNode.setAttribute("checked", "checked");
		    }
		    formNode.appendChild(inputNode);
	    }
	},
	/**
     * Enum field
     * @property __enum
     */
	__enum: {
	    build: function(formNode,fieldID,fieldObject){
		    if (fieldObject["label"]) {
			var lblNode = document.createElement("span");
			lblNode.innerHTML = fieldObject["label"];
			formNode.appendChild(lblNode);
		    }

		    var fieldValues = fieldObject["values"];
		    if (fieldObject["values"] instanceof Array) {
			fieldValues = {};
			for (var fieldValIdx = 0 ; fieldValIdx < fieldObject["values"].length ; fieldValIdx++) {
			    fieldValues[fieldValIdx] = fieldObject["values"][fieldValIdx];
			}
		    }

		    // Here we face a choice : do we use radio or select ?
		    if (fieldObject["radio"]) {
			// If the field is not required the user is not forced to make a choice
			if(!fieldObject["required"]) {
			    var radio = document.createElement("input");
			    radio.setAttribute("id", "empty"+fieldID);
			    radio.setAttribute("name", fieldID);
			    radio.setAttribute("type", "radio");
			    radio.setAttribute("value", "");
			    if(fieldObject["value"] == "" || fieldObject["value"] == undefined)
				radio.setAttribute("checked", "checked");
			    formNode.appendChild(radio);

			    var label = document.createElement("label");
			    label.setAttribute("for", "empty" + fieldID);
			    label.innerHTML = "<em>[no choice]</em>";
			    formNode.appendChild(label);
			}
			for (var item in fieldValues) {
			    var radio = document.createElement("input");
			    radio.setAttribute("id", fieldID + item);
			    radio.setAttribute("name", fieldID);
			    radio.setAttribute("type", "radio");
			    radio.setAttribute("value", item);
			    if(fieldObject["value"] == item)
				radio.setAttribute("checked", "checked");
			    formNode.appendChild(radio);
			    var label = document.createElement("label");
			    label.setAttribute("for", fieldID + item);
			    label.innerHTML = fieldValues[item];
			    formNode.appendChild(label);
			}
		    } else {
			var select = document.createElement("select");
			select.setAttribute("name", fieldID);
			select.setAttribute("id", fieldID);

			if(!fieldObject["required"]) {
			    var option = document.createElement("option");
			    option.setAttribute("value", "");
			    option.innerHTML = "";
			    select.appendChild(option);
			}

			for(item in fieldValues) {
			    var option = document.createElement("option");
			    option.setAttribute("value", item);
			    if(fieldObject["value"] == item)
				option.setAttribute("selected", "selected");
			    option.innerHTML = fieldValues[item];
			    select.appendChild(option);
			}
			formNode.appendChild(select);
		    }
	    }
	}
    },
    //******************************************END OF FIELDS***************************************
    
    /**
     * Seems to build the DOM
     * @method buildForm
     */
    buildForm: function() {
	//Declaring the targetNode as the node for the form
	var formNode = this.targetNode;
	var Node = document.createElement("fieldset");
	var legend = document.createElement("legend");
	legend.innerHTML = "Formulaire";
	Node.appendChild(legend);
	formNode.appendChild(Node);
	//Setting the method to post
	formNode.setAttribute("method", "post");
	//WTF is this ?... isn't there better way to do that ?
	//BTW adding onsubmit event in a horrible way
	formNode.setAttribute("onsubmit", "return KForm.getFormFromNode(this).submit();");
	//Now we are going through every field to generate them
	//@todo clean this loop and breaking it in multipart loop
	for (var fieldID in this.formFields) {
	    var fieldObject = this.formFields[fieldID];
	    var fieldType = "__" + fieldObject["type"];
	    if (this.fieldsType[fieldType])
		    this.fieldsType[fieldType].build(Node, fieldID, fieldObject, formNode);
	    //Please don't do designing with br !
	    formNode.appendChild(document.createElement("br"));
	}
	var submitNode = document.createElement("input");
	submitNode.setAttribute("type", "submit");
	//@todo something for translation here
	submitNode.setAttribute("value", "Envoyer");
	formNode.appendChild(submitNode);

	if (this.cancelCallBack) {
	    var cancelNode = document.createElement("input");
	    cancelNode.setAttribute("type", "button");
	    //Why the fuck it's in english here and in french some line earlier Oo
	    //@todo translation here too
	    cancelNode.setAttribute("value", "Cancel");
	    //@todo clear this messy thing
	    cancelNode.setAttribute("onclick", "KForm.getFormFromNode(this.parentNode).cancel();");
	    formNode.appendChild(cancelNode);
	}
    },
    /**
     * Didn't understood yet what it is used for.
     * @method getFormFromNode
     * @param node The node you want to i still don't know what it is used for
     */
    getFormFromNode: function (node) {
	for (var idx = 0 ; idx < KForm.forms.length ; idx++) {
	    var form = KForm.forms[idx];
	    //Wait wut ?
	    if (form[0] == node) {
		return form[1];
	    }
	}
	return null;
    },
    /**
     * Called when the form is cancelled
     * @method cancel
     */
    cancel: function () {
	if (this.cancelCallBack) {
	    if (this.cancelCallBack instanceof Array) {
		//Aren't there some verification needed ?
		this.cancelCallBack[0][this.cancelCallBack[1]]();
	    } else if (this.cancelCallBack instanceof Function) {
		this.cancelCallBack();
	    }
	}
    },
    /**
     * Called when the form is submitted
     * @method submitted
     */
    submitted: function () {
	// This function is called after content has been sent...
	if (this.submitCallBack instanceof Array) {
	    //Aren't there some verification needed ?
	    this.submitCallBack[0][this.submitCallBack[1]]();
	} else if (this.submitCallBack instanceof Function) {
	    this.submitCallBack();
	}
    },
    /**
     * Seems to verify every field when you submit a form.
     * @method submit
     */
    submit: function () {
	// Check the fields in the form
	// Warn if there is something invalid
	// Then send the content
	// Ho, BTW, use some nice hacks to submit files...
	// http://www.openjs.com/articles/ajax/ajax_file_upload/ and
	// http://www.openjs.com/articles/ajax/ajax_file_upload/response_data.php
	var queryComponents = new Array();
	for (var paramName in this.extraParameters)
	    queryComponents.push(paramName + "=" + encodeURIComponent(this.extraParameters[paramName]));
	var containsFile = false;

	for (var fieldID in this.formFields) {
	    var fieldObject = this.formFields[fieldID];
	    if ((fieldObject["type"] == "span") || (fieldObject["type"] == "help"))
		continue;
	    var formObject = getSubElementById(fieldID, this.targetNode);
	    if (fieldObject["type"] == "text") {
		if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
		    if (formObject.value == "") {
			alert("One or more fields are missing.");
			formObject.focus();
			return false;
		    }
		}
		queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
	    } else if (fieldObject["type"] == "password") {
		if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
		    if (formObject.value == "") {
			alert("One or more fields are missing.");
			formObject.focus();
			return false;
		    }
		}
		queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(encryptedString2(KeyPair, formObject.value)));
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
		var containsFile = true;
		if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
		    if (formObject.value == "") {
			alert("One or more fields are missing.");
			formObject.focus();
			return false;
		    }
		}
	    } else if ((fieldObject["type"] == "int") || (fieldObject["type"] == "float")) {
		// Is the field required ?
		if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
		    if (formObject.value == "") {
			alert("One or more fields are missing.");
			formObject.focus();
			return false;
		    }
		}

		// Is the field filled ?
		if (formObject.value != "") {
		    var num = Number(formObject.value);
		    var min = fieldObject["min"];
		    var max = fieldObject["max"];

		    // Test of the content : is it really a number, and an integer if needed ?
		    if (num.toString() == "NaN" || ((Math.round(num) != num) && fieldObject["type"] == "int")) {
			alert("Field value is not a valid number, you must enter a int");
			formObject.focus();
			return false;
		    }

		    // If there is a min, is it greater than the min ?
		    if (fieldObject["min"] != undefined && (num < min)) {
			var label = (fieldObject["label"]) ? fieldObject["label"] : "Number";
			alert(label + " (value: " + num.toString() + ") should not be smaller than " + min.toString());
			formObject.focus();
			return false;
		    }
		    // If there is a max, is it smaller than the max ?
		    if (fieldObject["max"] != undefined && (num > max)) {
			var label = (fieldObject["label"]) ? fieldObject["label"] : "Number";
			alert(label + " (value: " + num.toString() + ")  should not be greater than " + max.toString());
			formObject.focus();
			return false;
		    }
		}
				
		// Now all the tests are passed, we add the value to the query
		queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(formObject.value));
	    } else if (fieldObject["type"] == "enum") {
		var value = "";
		if (fieldObject["radio"]) {
		    var inputs = this.targetNode.getInputs('radio', fieldID);
		    for (radio in inputs) {
			if(inputs[radio].checked) {
			    value = inputs[radio].value;
			}
		    }
		    if(value == "" && fieldObject["required"]) {
			alert("You did not chose anything !");
			inputs[0].focus();
			return false;
		    }
		} else {
		    value = formObject.value;
		}
		queryComponents.push(encodeURIComponent(fieldID) + "=" + encodeURIComponent(value));
	    } else if (fieldObject["type"] == "bool") {
		if ((fieldObject["required"]) && (fieldObject["required"] == true)) {
		    alert("How can you use required on a bool ???");
		    return false;
		}
		queryComponents.push(encodeURIComponent(fieldID) + "=" + formObject.checked);
	    } else {
		alert("Hooo no, I can't do this !");
		return false;
	    }
	}
	if (containsFile) {
	    var iframeName = "iframe_" + (new Date()).getTime();
	    var iframeNode = document.createElement("iframe");
	    iframeNode.setAttribute("src", "");
	    iframeNode.setAttribute("name", iframeName);
	    iframeNode.setAttribute("id", iframeName);
	    //Debug : iframeNode.setAttribute("style", "border: 1px solid rgb(204, 204, 204); width: 100px; height: 100px; color: white;");
	    iframeNode.style.display = "none";
	    this.targetNode.appendChild(iframeNode);
	    this.targetNode.setAttribute("target", iframeName);
	    for (var paramName in this.extraParameters) {
		// Put every extra parameters in a hidden field...
		var hiddenNode = document.createElement("input");
		hiddenNode.setAttribute("type", "hidden");
		hiddenNode.setAttribute("name", paramName);
		hiddenNode.setAttribute("id", paramName);
		hiddenNode.setAttribute("value", this.extraParameters[paramName]);
		this.targetNode.appendChild(hiddenNode);
	    }
	    iframeNode.onload = function () {
		KForm.getFormFromNode(this.parentNode).submitted(); return true;
	    };
	    return true;
	} else {
	    var postData = queryComponents.join('&');
	    var url = this.targetNode.attributes.getNamedItem("action").nodeValue;
	    new Ajax.Request(url, {
		asynchronous: true,
		evalScripts: false,
		method: 'post',
		postBody: postData,
		form: this,
		onComplete: function(transport) {
		    var form = transport.request.options.form;
		    form.submitted();
		}
	    });
	    return false;
	}
	return false;
    }
});

