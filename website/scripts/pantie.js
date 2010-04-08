/**
 * @copyright 2010 Rémy Sanchez <remy.sanchez@hyperthese.net>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 */

/**
 * Provides a way to have HTTP server push
 */
function Pantie(register_url, uid) {
	// Session identifier for the event system
	var sid;
	// Events we are listening to
	var listen = undefined;
	// Number of tries remaining after an error
	var tries = 10;
	// The object itself
	var obj = this;
	// XHR object used to make queries
	var xhr = false;
	// Continue to run ?
	var run = false;
	// Url to push to
	var push_url;

	/**
	 * Registers the event evt
	 */
	var register = function(evt) {
		var good = true;

		var x = new Ajax.Request(register_url, {
			asynchronous: false,
			method: 'get',
			parameters: {
				session: sid,
				"event": evt
			},
			onSuccess: function(r) {
				if(r.responseJSON.slap == undefined) {
					push_url = KGlobals.baseurl + r.responseJSON.push_url;
				} else {
					alert('Pirate !');
				}
			},
			onFailure: function() {
				good = false;
			},
			onException: function() {
				good = false;
			}
		});

		return good;
	}

	/**
	 * Generates an unique string
	 */
	var generateSessionId = function() {
		var d = new Date();
		var id1 = (d.getTime() + Math.floor(Math.random()*10000000)) % 100000000;
		var id2 = (d.getTime() + Math.floor(Math.random()*10000000)) % 100000000;
		var id3 = (d.getTime() + Math.floor(Math.random()*10000000)) % 100000000;
		var id4 = (d.getTime() + Math.floor(Math.random()*10000000)) % 100000000;
		return id1.toString() + id2.toString() + id3.toString() + id4.toString()
	}

	/**
	 * Calls all the functions attached to a specific event
	 */
	var dispatchEvent = function(evt, data) {
		listen[evt].each(function(v, i) {
			v(data);
		});
	}

	/**
	 * Listen to an event, call callback when event occurs.
	 *
	 *    callback = function(data) {
	 *        alert(data);
	 *    }
	 */
	this.listenTo = function(evt, callback) {
		var tostart = false;
		if(listen == undefined) {
			listen = [];
			tostart = true;
		}

		if(listen[evt] == undefined) {
			listen[evt] = new Array();
		}

		if(register(evt)) {
			listen[evt].push(callback);
		}

		if(tostart) this.longPoll();
	}

	/**
	 * Start a long poll of the server, waiting for events
	 */
	this.longPoll = function() {
		var evts = [];
		run = true;

		for(k in listen) {
			if(typeof(listen[k]) == "object") evts.push(k);
		}

		xhr = new Ajax.Request(push_url, {
			method: 'post',
			parameters: {
				"session": sid,
				"events[]": evts
			},
			onFailure: function() {
				if(--tries > 0) {
					if(run) setTimeout(obj.longPoll, 1000);
				} else {
					alert('Aaaaaaaaargh... You\'d better reload the page ;-)');
				}
			},
			onSuccess: function(r) {
				data = r.responseJSON;

				// Is there any data in a first place ?
				if(data == null || data == undefined) {
					if(run) setTimeout(obj.longPoll, 1000);
					return;
				}

				// in case of error, we retry in 1 second (provided that we did not tried that
				// too much yet
				if(data.error == true) {
					if(--tries > 0) {
						if(run) setTimeout(obj.longPoll, 1000);
					}
					return;
				}

				// in case of timeout, let's just re-run the same thing
				if(data.timeout == true) {
					if(run) obj.longPoll();
					return;
				}

				// ok, we've got datas
				data.each(function(v, i) {
					if(listen[data[i].name] != undefined) {
						try {
							dispatchEvent(data[i].name, data[i].data);
						} catch(err) {
							// ok we just don't want to die stupidly because of an
							// incompetent programmer
						}
					}
				});
				if(run) obj.longPoll();
			}
		});
	}

	/**
	 * Close the connection
	 */
	this.close = function() {
		if(run) {
			run = false;
			xhr.abort();
		}
	}

	// On creation of the object, a session ID is generated
	sid = generateSessionId();
}
