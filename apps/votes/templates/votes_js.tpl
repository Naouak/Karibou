{literal}
Votes = {
	_base: function (vote, id, cback) {
{/literal}
		var url = "{kurl app="votes" page=""}/" + id + "," + vote;
{literal}
		new Ajax.Request(url, { onSuccess: function(response) {
				if (response.responseText == "ok") {
					alert("##VOTE_OK##");
					if (cback != undefined)
						cback();
				} else {
					alert("##VOTE_ERROR##");
				}
			}
		});
	},
	more: function (id, cback) {
		Votes._base(1, id, elem, cback);
	},
	less: function (id, cback) {
		Votes._base(-1, id, elem, cback);
	}
};
{/literal}

