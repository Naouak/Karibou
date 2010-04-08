/**
 * @copyright 2009 Pinaraf <pinaraf@ducroquet.info>
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 */

/**
 * Open a popup.
 */
function popup(url, name, height, width, top, left) {
	window.open(url, name, 'menubar=false, status=false, location=false, scrollbar=false, resizable=false, height='+height+', width='+width+', top='+top+', left='+left);
}

/**
* Keyboard shortcuts handler
*/
Event.observe(document, "keypress", function(evt) {
	var key = evt.charCode;
	var inputType = evt.element().tagName.toLowerCase();
	// Disable keyboard shortcuts when an input field is focused
	if (inputType != 'input' && inputType != 'textarea' && inputType != 'select') {
		// r : refresh flashmails
		if (key == 114)
			FlashmailManager.Instance.refreshFlashmails();
		// f : focus search field
		if (key == 102)
			document.forms['search'].elements['keywords'].focus();
	}
});

Event.observe(window, "load", function(evt) {
	Ajax.Base.prototype._initialize = Ajax.Base.prototype.initialize;

	Ajax.Base.prototype.initialize = function(options) {
			if (options.method == undefined)
				options.method = "get";
			this._initialize(options);
		};

	Ajax.Request.prototype.abort = function() {
		this.transport.onreadstatechange = Prototype.emptyFunction;
		this.transport.abort();
		Ajax.activeRequestCount--;
	}
});

/**
 * BBCode parser
 */

function KBBCode() {
	function makeSmileysUrl(theme) {
		return KGlobals.baseurl + '/kbbcode/emoticons/' + theme;
	}

	function unHtmlEntities(str) {
		var t = new Element('textarea');
		t.innerHTML = str.replace(/</g, '&lt;').replace(/>/g, '&gt;');
		return t.value; // This is not a hack
	}

	this.loadSmileys = function(theme, onDone) {
		this.e_theme_loaded = false;

		// Try to load it
		new Ajax.Request(makeSmileysUrl(theme), {
			method: 'get',
			onSuccess: function(t) {
				// We've got the theme
				this.e_theme = jsonParse(t.responseText);
				this.e_theme_loaded = true;
				// And now trigger the callback
				onDone();
			}.bind(this)
		});
	}

	this.process = function(str, richText) {
		var out = str;

		out = this.urlize(out);
		out = this.stringToDom(out, richText).innerHTML;
		return out;
	};

	this.setNick = function(nick) {
		this.nick = nick.replace(/([\\\.\$\[\]\(\)\{\}\^\?\*\+\-\^\|])/ig, '\\$1');
	};

	this.urlize = function(str) {
		expr = /(\s|^)((http|https|ftp|gopher):\/\/([\w\-]+\.)*([\w\-]+(:\d+\/?)?)(\/[^\s\[]*)*)(\s|$)/ig;
		str = str.replace(expr, "$1[url]$2[/url]$8");
		return str;
	};

	this.treatPureText = function(str, ml) {
		function wordWrap(str, ml) {
			if(ml == undefined) ml = 20;
			var out = "";
			var wl = 0;
			var exp = /[\s\-,.;<>]/i;
			for(var i=0; i < str.length; i++) {
				if(exp.match(str[i])) {
					wl = 0;
					out += str[i];
				} else {
					out += str[i];
					if(++wl >= ml) {
						out += " ";
						wl = 0;
					}
				}
			}
			return out;
		}

		function pseudoize(str) {
			var expr = new RegExp('([^\\w]|^)(' + this.nick + ')([^\\w]|$)', "ig");
			return str.replace(expr, '$1<strong>$2</strong>$3');
		}

		function smile(str) {
			if(this.e_theme_loaded == true) {
				try {
					this.e_theme.strings.each(function(v) {
						if(v.str == "") return;
						var expr = new RegExp('(\\s|^)(' + v.str + ')((?!\s)(\\s|$))', "g");
						str = str.replace(expr, "$1<img src='" + this.e_theme.picts[v.pict] + "' alt='$2' />$3");
					}, this);
				} catch(err) {
				}
			}

			return str;
		}

		if(navigator.appName != "Microsoft Internet Explorer") {
			var out = wordWrap(str, ml);
			out = pseudoize.bind(this)(out);
			out = smile.bind(this)(out);
		} else {
			out = str;
		}

		return out;
	}

	this.stringToDom = function(str, richtext) {
		var parts = str.split('[');

		var root = new Element('p');
		var node = root;
		node.tagname = "";
		var exp = /^(\/?)(\w*)/;

		for(var i = 0; i < parts.size(); i++) {
			var semi = parts[i].split(']', 2);

			var res = exp.exec(semi[0]);

			if(res == null || semi[1] == undefined) {
				node.innerHTML += this.treatPureText(((i != 0) ? '[' : '') + parts[i]);
			} else if(res[1] == "/" && res[2] == node.tagname) {
				node = node.parentNode;
				node.innerHTML += this.treatPureText(semi[1]);
			} else if(res[2] == "b") {
				var newNode = (richtext) ? new Element('strong') : new Element('span');
				newNode.innerHTML = this.treatPureText(semi[1]);
				newNode.tagname = "b";
				node.insert({
					bottom: newNode
				});
				node = newNode;
			} else if(res[2] == "i") {
				var newNode = (richtext) ? new Element('em') : new Element('span');
				newNode.innerHTML = this.treatPureText(semi[1]);
				newNode.tagname = "i";
				node.insert({
					bottom: newNode
				});
				node = newNode;
			} else if(res[2] == "url") {
				var prop = semi[0].split("=", 2);
				var href = unHtmlEntities((prop[1] != undefined) ? prop[1] : semi[1]);
				var short_href = href;

				var validate = /^(http|https|ftp|gopher):\/\//;
				if(!validate.match(href)) {
					href="";
				} else {
					short_href = href.replace(validate, "");
					short_href = short_href.replace(/\/$/, "");
					short_href = (short_href.length > 25) ? short_href.substr(0, 20) + "[...]" : short_href;
				}

				var newNode = new Element('a', {href: href, target: "_blank"});
				newNode.tagname = "url";
				newNode.innerHTML = this.treatPureText((prop[1] != undefined) ? semi[1] : short_href);
				node.insert({
					bottom: newNode
				});
				node = newNode;
			} else if(res[2] == "color") {
				var newNode = new Element('span');
				newNode.innerHTML = this.treatPureText(semi[1]);
				newNode.tagname = "color";

				var prop = semi[0].split('=', 2);
				if(prop[1] != undefined && richtext) {
					newNode.style.color = prop[1];
				}

				node.insert({
					bottom: newNode
				});
				node = newNode;
			} else {
				node.innerHTML += this.treatPureText(((i != 0) ? '[' : '') + parts[i]);
			}
		}

		return root;
	};
}
