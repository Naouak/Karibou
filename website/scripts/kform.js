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
				var spanNode = document.createElement("span");
				spanNode.innerHTML = fieldObject["text"];
				formNode.appendChild(spanNode);
			} else if (fieldObject["type"] == "help") {
				var titleNode = document.createElement("a");
				titleNode.setAttribute("href", "#");
				if (fieldObject["title"])
					titleNode.innerHTML = fieldObject["title"];
				else
					titleNode.innerHTML = "Help ?";
				formNode.appendChild(titleNode);
				helpNode = document.createElement("span");
				helpNode.setAttribute("style", "display: none;");
				helpNode.innerHTML = fieldObject["text"];
				helpNode.id = "__karibou_help_node_" + Math.ceil(Math.random()*1000000);
				titleNode.setAttribute("onclick", "new Effect.toggle(document.getElementById('" + helpNode.id + "')); return false;");
				formNode.appendChild(helpNode);
				helpNode.insertBefore(document.createElement("br"), helpNode.firstChild);
			} else if ((fieldObject["type"] == "text") || (fieldObject["type"] == "url") || (fieldObject["type"] == "password")) {
				if (fieldObject["label"]) {
					var lblNode = document.createElement("label");
					lblNode.innerHTML = fieldObject["label"];
					lblNode.setAttribute("for", fieldID);
					formNode.appendChild(lblNode);
				}
				var inputNode = document.createElement("input");
				inputNode.setAttribute("id", fieldID);
				inputNode.setAttribute("name", fieldID);

				if(fieldObject["type"] != "password")
					inputNode.setAttribute("type", "text");
				else
					inputNode.setAttribute("type", "password");

				if (fieldObject["maxlength"])
					inputNode.setAttribute("maxlength", fieldObject["maxlength"]);
				if (fieldObject["value"])
					inputNode.setAttribute("value", fieldObject["value"]);
				formNode.appendChild(inputNode);
			} else if (fieldObject["type"] == "date") {
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
			} else if (fieldObject["type"] == "textarea") {
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
			} else if (fieldObject["type"] == "file") {
				formNode.setAttribute("enctype", "multipart/form-data");
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
			} else if (fieldObject["type"] == "int" || fieldObject["type"] == "float") {
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
			} else if (fieldObject["type"] == "bool") {
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
			} else if(fieldObject["type"] == "enum") {
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

					for (item in fieldValues) {
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
			} else {
				alert("Unknown field type " + fieldObject["type"]);
			}
			formNode.appendChild(document.createElement("br"));
		}
		var submitNode = document.createElement("input");
		submitNode.setAttribute("type", "submit");
		submitNode.setAttribute("value", "Envoyer");
		formNode.appendChild(submitNode);

		if (this.cancelCallBack) {
			var cancelNode = document.createElement("input");
			cancelNode.setAttribute("type", "button");
			cancelNode.setAttribute("value", "Cancel");
			cancelNode.setAttribute("onclick", "KForm.getFormFromNode(this.parentNode).cancel();");
			formNode.appendChild(cancelNode);
		}
	},
	getFormFromNode: function (node) {
		for (var idx = 0 ; idx < KForm.forms.length ; idx++) {
			var form = KForm.forms[idx];
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
			var fieldObject = this.formFields[fieldID];
			if ((fieldObject["type"] == "span") || (fieldObject["type"] == "help"))
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
			for (paramName in this.extraParameters) {
				// Put every extra parameter in a hidden field...
				var hiddenNode = document.createElement("input");
				hiddenNode.setAttribute("type", "hidden");
				hiddenNode.setAttribute("name", paramName);
				hiddenNode.setAttribute("id", paramName);
				hiddenNode.setAttribute("value", this.extraParameters[paramName]);
				this.targetNode.appendChild(hiddenNode);
			}
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

