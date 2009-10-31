{literal}
Votes = {
	_base: function (vote, id, elem) {
{/literal}
		var url = "{kurl app="votes" page=""}/" + id + "," + vote;
{literal}
		new Ajax.Request(url, { onSuccess: function(response) {
				if (response.responseText == "ok") {
					alert("##VOTE_OK##");
					if (elem != undefined) {
						new Effect.toggle(elem);
					}
				} else {
					alert("##VOTE_ERROR##");
				}
			}
		});
	},
	more: function (id, elem) {
		Votes._base(1, id, elem);
	},
	less: function (id) {
		Votes._base(-1, id, elem);
	}
};
{/literal}

