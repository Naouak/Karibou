{literal}

var pollClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.refreshURL = {/literal}'{kurl page="miniVote"}';{literal}
		this.voteURL = {/literal}'{kurl page="miniVote"}';{literal}
		this.canVote = false;
		if (this.getElementById("canVote"))
			this.canVote = true;
		if (!this.canVote) {
			this.refresher = new Ajax.PeriodicalUpdater(this.getElementById('poll_votes'), this.refreshURL, {asynchronous:true, frequency: 3600});
			this.refresher.start();
		}		
	},
	vote: function (answerID) {
		if (this.canVote)
			new Ajax.Updater(this.getElementById('poll_votes'), this.voteURL, {method: 'post', parameters: 'voteAnswer=' + answerID});
		this.canVote = false;
	},
	onRefresh: function () {
		if (this.refresher) {
			this.refresher.stop();
			this.refresher = new Ajax.PeriodicalUpdater(this.getElementById('poll_votes'), this.refreshURL, {asynchronous:true, frequency: 3600});
			this.refresher.start();
		}
	}
});

{/literal}
