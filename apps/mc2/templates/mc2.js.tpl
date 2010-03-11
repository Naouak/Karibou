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
		this.list = this.getElementById('messages');
		this.msg = [];

		// Display the initial messages
		this.updateState();

		// Wait for updates (broadcast event for all messages)
		pantie.listenTo('mc2-*-message', function(msg) {
			obj.onNewMessage(msg);
		});
	},

	/**
	 * When the config changes, you must update some details, like rebuilding
	 * the whole DOM tree.
	 */
	onConfig: function() {
		this.updateState();
	},

	/**
	 * What happens when a new message arrives from the pantie system.
	 */
	onNewMessage: function(msg) {
		try {
			this.appendMessage(msg.evalJSON());
		} catch(err) {
			console.log(err);
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
				obj.msg = t.responseJSON;
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
				var d = $(this.list).immediateDescendants();
				d[d.size() - 1].remove();
			}
			this.list.innerHTML = this.makeLine(msg) + this.list.innerHTML;
		} else {
			if(pop) {
				var d = $(this.list).immediateDescendants();
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
		return "<li><strong>" + msg.userlink + "</strong> (<em>" + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds() + "</em>) " + msg.post + "</li>";
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
