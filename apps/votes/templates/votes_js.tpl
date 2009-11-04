{literal}
Votes = {
	_base: function (vote, id, cback) {
{/literal}
		var url = "{kurl app="votes" page=""}/" + id + "," + vote;
{literal}
		new Ajax.Request(url, { onSuccess: function(response) {
				if (response.responseText == "ok") {
					if (cback != undefined)
						cback();
				}
			}
		});
	},
	more: function (id, cback) {
		Votes._base(1, id, cback);
	},
	less: function (id, cback) {
		Votes._base(-1, id, cback);
	}
};
{/literal}

