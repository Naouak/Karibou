{literal}

var FlashmailManager = Class.create({
    initialize: function () {
        this.originalDocumentTitle = document.title;
        this.blinkedTitle = "";
        this.blinkerInterval = null;
        this.blinkerState = false;
        this.blinking = false;
        FlashmailManager.Instance = this;
        this.flashmailBox = document.createElement("div");
        this.flashmailBox.setAttribute("class", "flashmails");
        this.currentFlashmails = [];
        document.body.appendChild(this.flashmailBox);
        this.refreshFlashmails();
        setInterval('FlashmailManager.Instance.refreshFlashmails()', 30000);
    },
    blinker: function () {
        FlashmailManager.Instance.blinkerState = !FlashmailManager.Instance.blinkerState;
        if (FlashmailManager.Instance.blinkerState)
            document.title = FlashmailManager.Instance.blinkedTitle;
        else
            document.title = FlashmailManager.Instance.originalDocumentTitle;
    },
    stop_blinker: function () {
        if (this.blinking)
            clearInterval(this.blinkerInterval)
        document.title = this.originalDocumentTitle;
        this.blinkerInterval = null;
        this.blinking = false;
    },
    start_blinker: function () {
        if (this.blinking)
            clearInterval(this.blinkerInterval);
        this.blinkerInterval = setInterval(FlashmailManager.Instance.blinker, 1000);
        this.blinking = true;
    },
    refreshFlashmails: function () {
        new Ajax.Request('{/literal}{kurl app="flashmail" page="unreadlistXML"}{literal}', {asynchronous: true, method: 'get', onSuccess: function (transport) {
            FlashmailManager.Instance.handleFlashmails(transport);
        }});
    },
    markAsRead: function (flashmailId) {
        url = '{/literal}{kurl app="flashmail" page="setasread" flashmailid=""}{literal}' + flashmailId;
        new Ajax.Request(url, {asynchronous: true, onSuccess: function (transport) {
            FlashmailManager.Instance.refreshFlashmails();
        }});
    },
    newFlashmail: function (targetUserName, targetUserId) {
        divNode = document.createElement("div");
        divNode.setAttribute("class", "flashmail compose");
        subDivNode = document.createElement("div");
        subDivNode.innerHTML = "##TO## " + targetUserName;
        divNode.appendChild(subDivNode);
        divNode.appendChild(document.createElement("br"));
        formNode = document.createElement("form");
        
        txtNode = document.createElement("textarea");
        txtNode.setAttribute("name", "message");
        txtNode.setAttribute("cols", "40");
        txtNode.setAttribute("rows", "6");
        formNode.appendChild(txtNode);
        
        inputNode = document.createElement("input");
        inputNode.setAttribute("type", "hidden");
        inputNode.setAttribute("name", "to_user_id");
        inputNode.setAttribute("value", targetUserId);
        formNode.appendChild(inputNode);
        
        brNode = document.createElement("br");
        formNode.appendChild(brNode);
        
        inputNode = document.createElement("input");
        inputNode.setAttribute("value", "##SEND##");
        inputNode.setAttribute("type", "submit");
        formNode.appendChild(inputNode);
        
        // Add a cancel button
        inputNode = document.createElement("input");
        inputNode.setAttribute("type", "button");
        inputNode.setAttribute("value", "##CLOSE##");
        $(inputNode).observe('click', function (evt) {
            $(this.parentNode.parentNode).fade({duration: 1.0, afterFinish: function (div) {
                if (div.element.parentNode)
                    div.element.parentNode.removeChild(div.element);
            }});
        });
        formNode.appendChild(inputNode);
        
        divNode.appendChild(formNode);
        $(formNode).observe('submit', function (evt) {
            var queryComponents = new Array();
            queryComponents.push("to_user_id=" + encodeURIComponent(this.elements["to_user_id"].value));
            queryComponents.push("message=" + encodeURIComponent(this.elements["message"].value));
            
            var post_vars = queryComponents.join("&");

            new Ajax.Request('{/literal}{kurl app="flashmail" page="send"}{literal}', {
                    asynchronous:true,
                    evalScripts:false,
                    method:'post',
                    postBody:queryComponents.join("&")
                });
            evt.preventDefault();
            evt.stopPropagation();
            $(this.parentNode).fade({duration: 1.0, afterFinish: function (div) {
                div.element.parentNode.removeChild(div.element);
            }});
            FlashmailManager.Instance.refreshFlashmails();
            return false;
        });
        this.flashmailBox.appendChild(divNode);
        txtNode.focus();
    },
    handleFlashmails: function (transport) {
        flashmailsNode = transport.responseXML.firstChild;
        newFlashmails = [];
        if (flashmailsNode.childNodes.length > 0) {
            this.blinkedTitle = "Flashmails !";
            this.start_blinker();
            fullDate = "";
            shortDate = "";
            authorName = "";
            authorLink = "";
            messageText = "";
            oldMessageText = "";
            flashClass = "";
            flashId = "";
            for (var i = 0 ; i < flashmailsNode.childNodes.length ; i++) {
                xmlNode = flashmailsNode.childNodes[i];
                if (xmlNode.nodeName != "flashmail")
                    continue;
                flashId = xmlNode.attributes.getNamedItem("id").nodeValue;
                newFlashmails.push(flashId);
                if (this.currentFlashmails.indexOf(flashId) != -1)
                    continue;
                this.currentFlashmails.push(flashId);
                flashClass = xmlNode.attributes.getNamedItem("class").nodeValue;
                for (var j = 0 ; j < xmlNode.childNodes.length ; j++) {
                    subNode = xmlNode.childNodes[j];
                    if (subNode.nodeName == "date") {
                        fullDate = subNode.attributes.getNamedItem("full").nodeValue;
                        shortDate = subNode.attributes.getNamedItem("short").nodeValue;
                    } else if (subNode.nodeName == "author") {
                        authorName = subNode.attributes.getNamedItem("name").nodeValue;
                        authorLink = subNode.attributes.getNamedItem("link").nodeValue;
                        authorId = subNode.attributes.getNamedItem("id").nodeValue;
                    } else if (subNode.nodeName == "message") {
                        messageText = subNode.firstChild.textContent;
                    } else if (subNode.nodeName == "oldmessage") {
                        oldMessageText = subNode.firstChild.textContent;
                    }
                }
                flashNode = document.createElement("div");
                flashNode.setAttribute("class", "flashmail");
                flashNode.setAttribute("flashmailId", flashId);
                flashNode.setAttribute("authorId", authorId);
                
                headNode = document.createElement("div");
                headNode.setAttribute("class", "flashmailHeader");
                
                spanNode = document.createElement("span");
                spanNode.setAttribute("class", "answerlink");
                aNode = document.createElement("a");
                aNode.innerHTML = "##REPLY##";
                $(aNode).observe('click', function () {
                    // Much much more fun !
                    // Build the answer form...
                    // action => /flashmail/send
                    // omsgid => original message id
                    // to_user_id => original author id
                    divNode = this.parentNode.parentNode.parentNode;
                    flashId = divNode.attributes.getNamedItem("flashmailId").nodeValue;
                    authorId = divNode.attributes.getNamedItem("authorId").nodeValue;
                    formNode = document.createElement("form");
                    msgNode = document.createElement("span");
                    msgNode.innerHTML = "Message :<br />";
                    formNode.appendChild(msgNode);
                    
                    txtNode = document.createElement("textarea");
                    txtNode.setAttribute("name", "message");
                    txtNode.setAttribute("cols", "40");
                    txtNode.setAttribute("rows", "6");
                    formNode.appendChild(txtNode);
                    
                    inputNode = document.createElement("input");
                    inputNode.setAttribute("type", "hidden");
                    inputNode.setAttribute("name", "omsgid");
                    inputNode.setAttribute("value", flashId);
                    formNode.appendChild(inputNode);
                    inputNode = document.createElement("input");
                    inputNode.setAttribute("type", "hidden");
                    inputNode.setAttribute("name", "to_user_id");
                    inputNode.setAttribute("value", authorId);
                    formNode.appendChild(inputNode);
                    
                    brNode = document.createElement("br");
                    formNode.appendChild(brNode);
                    
                    inputNode = document.createElement("input");
                    inputNode.setAttribute("type", "submit");
                    inputNode.setAttribute("value", "##SEND##");
                    formNode.appendChild(inputNode);
                    
                    $(formNode).observe('submit', function (evt) {
                        var queryComponents = new Array();
                        queryComponents.push("omsgid=" + encodeURIComponent(this.elements["omsgid"].value));
                        queryComponents.push("to_user_id=" + encodeURIComponent(this.elements["to_user_id"].value));
                        queryComponents.push("message=" + encodeURIComponent(this.elements["message"].value));
                        
                        var post_vars = queryComponents.join("&");

                        new Ajax.Request('{/literal}{kurl app="flashmail" page="send"}{literal}', {
                                asynchronous:true,
                                evalScripts:false,
                                method:'post',
                                postBody:queryComponents.join("&")
                            });
                        evt.preventDefault();
                        evt.stopPropagation();
                        $(this.parentNode).fade({duration: 1.0, afterFinish: function (div) {
                            if (div.element.parentNode) {
                                div.element.parentNode.removeChild(div.element);
                            }
                        }});
                        FlashmailManager.Instance.markAsRead(this.elements["omsgid"].value);
                        FlashmailManager.Instance.refreshFlashmails();
                        return false;
                    });
                    
                    // Add a cancel button
                    inputNode = document.createElement("input");
                    inputNode.setAttribute("type", "button");
                    inputNode.setAttribute("value", "##CLOSE##");
                    $(inputNode).observe('click', function (evt) {
                        formNode = this.parentNode;
                        divNode = formNode.parentNode;
                        msgNodes = divNode.getElementsByClassName("oldmessage");
                        for (var i = 0 ; i < msgNodes.length ; i++) {
                            oldMsg = msgNodes[i];
                            if (oldMsg.style.display != "none")
                                oldMsg.setAttribute("class", "message");
                            else
                                oldMsg.style.display = "block";
                        }
                        divNode.getElementsByClassName("answerlink")[0].childNodes[0].style.display = "";
                        divNode.removeChild(formNode);
                    });
                    formNode.appendChild(inputNode);
                    divNode.appendChild(formNode);
                    
                    // Hide the answer link...
                    this.style.display = "none";
                    // Change the old message style...
                    oldmessages = divNode.getElementsByClassName("oldmessage");
                    if (oldmessages.length > 0) {
                        for (var i = 0 ; i < oldmessages.length ; i++) {
                            oldmessages[i].style.display = "none";
                        }
                    }
                    divNode.getElementsByClassName("message")[0].setAttribute("class", "oldmessage");
                    txtNode.focus();
                });
                spanNode.appendChild(aNode);
                headNode.appendChild(spanNode);
                
                spanNode = document.createElement("span");
                spanNode.setAttribute("class", "readlink");
                aNode = document.createElement("a");
                aNode.innerHTML = "##SETASREAD##";
                $(aNode).observe('click', function () { 
                    FlashmailManager.Instance.markAsRead(this.parentNode.parentNode.parentNode.attributes.getNamedItem("flashmailId").nodeValue);
                });
                spanNode.appendChild(aNode);
                headNode.appendChild(spanNode);
                
                spanNode = document.createElement("span");
                spanNode.setAttribute("class", "time");
                acroNode = document.createElement("acronym");
                acroNode.setAttribute("title", fullDate);
                acroNode.textContent = shortDate;
                spanNode.appendChild(acroNode);
                headNode.appendChild(spanNode);
                
                spanNode = document.createElement("span");
                spanNode.setAttribute("class", "author");
                aNode = document.createElement("a");
                aNode.setAttribute("href", authorLink);
                aNode.textContent = authorName;
                spanNode.appendChild(aNode);
                headNode.appendChild(spanNode);
                flashNode.appendChild(headNode);
                
                if (oldMessageText != "") {
                    divNode = document.createElement("div");
                    divNode.setAttribute("class", "oldmessage");
                    while (oldMessageText.indexOf("\n") != -1)
                        oldMessageText = oldMessageText.replace("\n", "<br />");
                    divNode.innerHTML = oldMessageText;
                    flashNode.appendChild(divNode);
                }
                
                divNode = document.createElement("div");
                divNode.setAttribute("class", "message");
                while (messageText.indexOf("\n") != -1)
                    messageText = messageText.replace("\n", "<br />");
                divNode.innerHTML = messageText;
                flashNode.appendChild(divNode);
                
                this.flashmailBox.appendChild(flashNode);
            }
        } else {
            this.stop_blinker();
        }
        // Check the diff with newFlashmails
        // => delete useless flashmails...
        flashNode = this.flashmailBox.firstChild;
        while (flashNode) {
            if (flashNode.attributes.getNamedItem("class").nodeValue == "flashmail") {
                if (newFlashmails.indexOf(flashNode.attributes.getNamedItem("flashmailId").nodeValue) == -1) {
                    $(flashNode).fade({duration: 1.0, afterFinish: function (div) {
                        if (div.element.parentNode)
                            div.element.parentNode.removeChild(div.element);
                    }});
                }
            }
            flashNode = flashNode.nextSibling;
        }
        this.currentFlashmails = newFlashmails;
    }
});

Event.observe(window, "load", function() {
    flashmailManager = new FlashmailManager();
});

{/literal}