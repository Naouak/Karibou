alert("Piou piou piou");
{literal}
Votes = {
	more: function (id) {
{/literal}
		var url = "{kurl app="votes" page=""}/" + id + ",1";
{literal}
		new Ajax.Request(url, { onSuccess: function(response) {
				alert(response.responseText);
			}
		});
	},
	less: function (id) {
{/literal}
		var url = "{kurl app="votes" page=""}/" + id + ",-1";
{literal}
		new Ajax.Request(url, { onSuccess: function(response) {
				alert(response.responseText);
			}
		});
	}
};

{/literal}
Votes.more("test");
Votes.less("test");

