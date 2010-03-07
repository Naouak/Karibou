/**
 * We have to extend Prototype a bit
 */

Ajax.Request.prototype.abort = function() {
	this.transport.onreadstatechange = Prototype.emptyFunction;
	this.transport.abort();
	Ajax.activeRequestCount--;
}

/**
 * Provides a way to have HTTP server push
 */
function Pantie(register_url, uid) {
	// Session identifier for the event system
	var sid;
	// Events we are listening to
	var listen = [];
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
				if(r.responseJSON.slap != undefined) {
					push_url = r.responseJSON.push;
					uid = r.responseJSON.id;
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
		for(i in listen[evt]) {
			listen[evt][i](data);
		}
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
		if(listen.size() == 0) {
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
		var evts = new Array();
		run = true;

		for(k in listen) {
			evts.push(k);
		}

		xhr = new Ajax.Request(push_url, {
			method: 'post',
			parameters: {
				"sessuion": sid,
				"events": evts
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
				for(i in data) {
					if(listen[data[i].name] != undefined) {
						dispatchEvent(data[i].name, data[i].data);
					}
				}
				if(run) obj.longPoll();
			}
		});

		/*xhr = $.ajax({
			cache: false,
			data: {
				"session": sid,
				"events": evts
			},
			dataType: "json",
			error: function(xhr, stat, error) {
				if(--tries > 0) {
					if(run) setTimeout(obj.longPoll, 1000);
				}
			},
			success: function(data, stat) {
				// Is there any data in a first place ?
				if(data == null) {
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
				for(i in data) {
					if(listen[data[i].name] != undefined) {
						dispatchEvent(data[i].name, data[i].data);
					}
				}
				if(run) obj.longPoll();
			},
			type: "POST",
			url: '/push.php'
		});*/
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
