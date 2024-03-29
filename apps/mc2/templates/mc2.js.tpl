{*
  Copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
  Copyright 2010 Pierre Ducroquet <pinaraf@pinaraf.info>

  License: http://www.gnu.org/licenses/gpl.html GNU Public License
  See the enclosed file COPYING for license information.
*}

// {literal}
// <script>

var mc2Class = Class.create(KApp, {
	/**
	* Initializes the application. If you want to know how it works, RTFM...
	* Hahahaha
	*/
	initialize: function($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);

		var obj = this;

		// Create an empty list of messages
		this.list = $(this.getElementById('messages'));
		this.msg = [];

		// Attach events
		this.listenToDom();

		// Create the BBCode parser
		this.bbc = new KBBCode();
		this.bbc.setNick(KGlobals.userNick);
		this.bbc.loadSmileys(this.config.emoticon_theme, function() {
			// When the smileys are loaded, you must repaint the DOM
			this.repaintDOM();
		}.bind(this));

		// Display the initial messages
		this.updateState();

		// Wait for updates (broadcast event for all messages)
		pantie.listenTo('mc2-*-message', this.onNewMessage.bind(this));
	},

	listenToDom: function() {
		var obj = this;

		// Override events of the form
		$(this.getElementById('msg_form')).observe('submit', function(evt) {
			this.request();
			this.msg.value = "";
			evt.stop();
		});

		// Focus effect on the input
		$(this.getElementById('input_text')).observe('focus', function(evt) {
			$(obj.getElementById('input_text')).parentNode.addClassName('mc2_input_focus');
			$(obj.getElementById('input_submit')).parentNode.addClassName('mc2_input_focus');
		});

		// Lost of focus
		$(this.getElementById('input_text')).observe('blur', function(evt) {
			$(obj.getElementById('input_text')).parentNode.removeClassName('mc2_input_focus');
			$(obj.getElementById('input_submit')).parentNode.removeClassName('mc2_input_focus');
		});

		// Autocompletion
		// TODO: no autocomplete with 0 character
		$(this.getElementById('input_text')).observe('keydown', this.onKeyDown.bind(this));
	},

	/**
	 * When the config changes, you must update some details, like rebuilding
	 * the whole DOM tree.
	 */
	onConfig: function() {
		this.placeFormRight();
		this.updateState();
		this.displayButton();
		this.bbc.loadSmileys(this.config.emoticon_theme, function() {
			// When the smileys are loaded, you must repaint the DOM
			this.repaintDOM();
		}.bind(this));
	},

	refresh: function() {
		this.updateState();
		return false;
	},

	/**
	 * What happens when a new message arrives from the pantie system.
	 */
	onNewMessage: function(msg) {
		try {
			var jmsg = jsonParse(msg);
			if(
				   (this.config.show_msg && jmsg.type == "msg")
				|| (this.config.show_score && jmsg.type == "score")
				|| (!this.config.show_msg && !this.config.show_score)
			) {
				this.appendMessage(jmsg);
			}
		} catch(err) {
		}
	},

	onKeyDown: function(evt) {
		if (evt.keyCode == 9) {	// 9 == Tab key
			var messageInput = this.getElementById("input_text");
			var currentText = messageInput.value;
			// Extract last word, ending at messageInput.selectionEnd...
			var lastWord = '';
			var i = messageInput.selectionEnd - 1;
			while (i >= 0) {
				var c = currentText[i];
				if ((c == ' ') || (c == "\t") || (c == ':') || (c == ',') || (c == ';'))
					break
				lastWord = c + lastWord;
				i--;
			}

			if(lastWord.length == 0) {
				evt.stop();
				return;
			}

			// Crude hack here to retrieve the list of onlineusers
			var oll = document.getElementById("onlineusers_live");
			if (oll) {
				var matches = oll.innerHTML.match(new RegExp("\\(\\)\">" + lastWord + "(.*)</a>", "gi"));
				// Sorry, I do not handle more than one nick for completion so far...
				if (matches.length == 1) {
					var daMatch = matches[0];
					var daName = daMatch.substring(4, daMatch.length-4);
					
					// Now, replace lastWord with daName
					var currentTextBefore = currentText.substring(0, messageInput.selectionEnd - lastWord.length);
					var currentTextAfter = currentText.substring(messageInput.selectionEnd);
					var newText = "";
					if (currentTextBefore.length < 2)
						newText = " :";
					newText = currentTextBefore + daName + newText + " " + currentTextAfter;
					messageInput.value = newText;
				}
			}
			evt.stop();
		}
	},

	placeFormRight: function() {
		var old_list = this.list;
		var par = $(old_list.parentNode);

		var new_list = old_list.cloneNode(true);
		this.list = new_list;

		old_list.remove();

		if(this.config.invert) {
			par.insert({bottom: new_list});
		} else {
			par.insert({top: new_list});
		}
	},

	displayButton: function() {
		if(this.config.button) {
			$(this.getElementById("input_submit")).parentNode.show();
			$(this.getElementById("input_text")).removeClassName('mc2_input_text_noradius');
		} else {
			$(this.getElementById("input_submit")).parentNode.hide();
			$(this.getElementById("input_text")).addClassName('mc2_input_text_noradius');
		}
	},

	/**
	 * Deletes the current DOM, fetches the list of current messages and rebuilds
	 * erything.
	 */
	updateState: function() {
		var obj = this;

		var types = [];
		if(this.config['show_msg']) types.push('msg');
		if(this.config['show_score']) types.push('score');

		new Ajax.Request(this.makeStateUrl(this.config['num_lines'], types), {
			method: 'get',
			onSuccess: function(t) {
				obj.msg = jsonParse(t.responseText);
				obj.repaintDOM();
			}
		});
	},

	/**
	 * Takes the messages currently loaded in memory and regenerate the DOM to
	 * display them.
	 */
	repaintDOM: function() {
		var obj = this;
		this.list.innerHTML = "";

		this.msg.each(function(v, k) {
			obj.appendMessageToDOM(v);
		});
	},

	/**
	 * Appends a message to the messages list, and then adds it to the DOM
	 */
	appendMessage: function(msg) {
		var pop = false;
		if(this.msg.size() >= this.config.num_lines) {
			pop = true;
			var i;
			for(i = 1; i < this.config.num_lines; i++) {
				this.msg[i-1] = this.msg[i];
			}
			this.msg[i] = msg;
		} else {
			this.msg[this.msg.size()] = msg;
		}
		this.appendMessageToDOM(msg, pop);
	},

	/**
	 * Appends a message to the DOM
	 */
	appendMessageToDOM: function(msg, pop) {
		if(this.config.invert) {
			if(pop) {
				var d = this.list.immediateDescendants();
				d[d.size() - 1].remove();
			}
			this.list.innerHTML = this.makeLine(msg) + this.list.innerHTML;
		} else {
			if(pop) {
				var d = this.list.immediateDescendants();
				d[0].remove();
			}
			this.list.innerHTML += this.makeLine(msg);
		}
	},

	/**
	 * Generates the HTML code for a single line
	 */
	makeLine: function(msg) {
		var d = new Date(msg.time);

		function makeTimeString(t) {
			var h = t.getHours();
			var m = t.getMinutes();

			if(h < 10) h = "0" + h;
			if(m < 10) m = "0" + m;

			return h + ":" + m;
		}

		try {
			if(msg.type == "msg" || msg.type == "score") {
				var post;
				var tpl;
				if(msg.post.substring(0, 4) != "/me ") {
					tpl = new Template('<li><span class="time">[#{time}]</span> <span class="user">#{user}</span> <span class="msg">#{msg}</span></li>');
					post = msg.post;
				} else {
					tpl = new Template('<li><span class="time">[#{time}]</span> <span class="me"><span class="user">#{user}</span> <span class="msg">#{msg}</span> </span></li>');
					post = msg.post.substring(4);
				}
				return tpl.evaluate({
					time: makeTimeString(d),
					user: msg.userlink,
					msg: this.bbc.process(post, this.config.richtext)
				});
			}
		} catch(err) {
			// you're fucked :)
		}
	},

	/**
	 * Generates the URL that will return the state according to the current
	 * configuration
	 */
	makeStateUrl: function(num_lines, types) {
		return KGlobals.baseurl + '/mc2/state/' + num_lines + ',' + types.join('|');
	}
});

// </script>
// {/literal}
