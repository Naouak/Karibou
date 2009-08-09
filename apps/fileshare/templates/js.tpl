{literal}

var fileshareClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.downloadBase = "{/literal}{kurl page="download"}{literal}";
		this.foldersInDOM = new Array();
		this.foldersInDOM["/"] = this.getElementById("rootFolder");
		this.loadFolder("/");
	},
	loadFolder: function (folderName) {
		new Ajax.Request("{/literal}{kurl page="json"}{literal}", {
			parameters: "folder=" + encodeURIComponent(folderName), 
			folder: folderName,
			app: this,
			onComplete: function(transport) {
				transport.request.options.app.loadedFolder(transport.request.options.folder, transport.responseText);
			}
		});
	},
	folderClick: function (node) {
		path = node.getAttribute("path");
		if (path == null)
			return;
		if (node.getAttribute("status") == "expanded") {
			node.setAttribute("status", "retracted");
			node.parentNode.className = "directory";
			Effect.BlindUp(this.foldersInDOM[path], {duration: 0.3});
		} else if (node.getAttribute("status") == "retracted") {
			node.setAttribute("status", "expanded");
			node.parentNode.className = "directory expanded";
			Effect.BlindDown(this.foldersInDOM[path], {duration: 0.3});
		} else {
			this.loadFolder(path);
			node.parentNode.className = "directory expanded";
			node.setAttribute("status", "expanded");
		}
	},
	loadedFolder: function (folderName, answerText) {
		parentNode = this.foldersInDOM[folderName];
		if (!parentNode) {
			alert("I'm sorry, I just triggered an internal error... Fear the DOOM !");
			alert(folderName);
			return;
		}
		var result = answerText.evalJSON();
		for (var idx = 0 ; idx < result["folders"].length ; idx++) {
			folder = result["folders"][idx];
			node = document.createElement("li");
			uNode = document.createElement("ul");
			uNode.className = "directory";
			node.className = "directory";
			uNode.setAttribute("onclick", "$app(this).folderClick(this);");
			aNode = document.createElement("a");
			aNode.setAttribute("href", "#");
			aNode.setAttribute("onclick", "javascript: $app(this).folderClick(this); return false;");
			aNode.innerHTML = folder.name;
			aNode.setAttribute("path", folder.path);
			node.appendChild(aNode);
			this.foldersInDOM[folder.path] = uNode;
			node.appendChild(uNode);
			parentNode.appendChild(node);
		}
		for (var idx = 0 ; idx < result["files"].length ; idx++) {
			file = result["files"][idx];
			node = document.createElement("li");
			aNode = document.createElement("a");
			aNode.href = this.downloadBase + file.download;
			aNode.innerHTML = file.name;
			node.className = "file " + file.extension;
			node.appendChild(aNode);
			parentNode.appendChild(node);
		}
	}
});

{/literal}
