// {literal}
// <script>

var mc2Class = Class.create(KApp, {
	initialize: function($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);

		this.msg = [];

		this.updateState();
	},

	onConfig: function() {
		this.updateState();
	},

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

	repaintDOM: function() {
		var list = this.getElementById('messages');
		list.innerHTML = "";

		this.msg.each(function(v, k) {
			list.innerHTML += "<li>" + v['post'] + "</li>";
		});
	},

	makeStateUrl: function(num_lines, types) {
		return '/mc2/state/' + num_lines + ',' + types.join('|');
	}
});

// </script>
// {/literal}
