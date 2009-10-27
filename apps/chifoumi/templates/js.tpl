// {literal}
// <script>

//hack Google Chrome: thanks Garbage collector and gloal vars defined randomly.
if(chifoumi != undefined){
	if(chifoumi.puller == undefined){
		chifoumi = undefined;
	}
}
//Pour permettre de lancer plusieurs instances de chifoumi sans que ça explose BOUM! \o/
if(chifoumi == undefined){
	var chifoumi = {};
	//Puller du chifoumi: va chercher les liste des challenges
	chifoumi.puller = function(){
		var that = {};
		var listeners = Array();
		var challenges = null;
		var id = 0;
		that.listenerscount = 0;
		//Ajoute un élément qui écoute la liste des challenges
		that.addListener = function(listener){
			listener.id = id++;
			listeners.push(listener);
			that.listenerscount++;
			that.refreshData();
		}
		
		that.removeListener = function(listener){
			var i =0;
			for(var list in listeners){
				if(listeners[list].id == listener.id){
					while(i<listeners.length){
						listeners[i] = listeners[++i];
					}
					delete listeners[list];
					that.listenerscount--;
					return;
				}
				i++;
			}
		}
		
		//Envoit à chaque écouteur le premier challenge de la liste
		that.sendData = function(){
			if(challenges[0] != undefined){
				for(var listener in listeners){
					listeners[listener].activeChallenge(challenges[0]);
					
				}
			}
		}
		//Met à jour la liste des challenges
		that.refreshData = function(){
			new Ajax.Request('{/literal}{kurl page="challenge"}{literal}', 
				{
					method: 'post',
					onSuccess: function(transport){
						var response = transport.responseText.evalJSON();
						challenges = response;
						that.sendData();
					}
				}
			);
		
		}
		
		var noChallenge = function(){
			if(challenges.length == 0 && that.listenerscount > 0){
				that.refreshData();
			}
		}
		new PeriodicalExecuter(noChallenge, 10);
		
		//Réponse à un challenge
		that.answerChallenge = function(id,weapon){
			new Ajax.Request('{/literal}{kurl page="challenge"}{literal}', 
				{
					method: 'post',
					parameters: "id="+id+"&weapon="+weapon,
					onSuccess: function(transport){
						var response = transport.responseText.evalJSON();
						if(response.result == 0){
							that.message("##No winner##");
						}
						else if(response.result == 1){
							that.message("##You won##");
						}
						else if(response.result == -1){
							that.message("##You lost##");
						}
						else{
							that.message("##Challenge Refused##");
						}
						that.refreshData();
					}
				}
			);
			for(var listener in listeners){
				if(listeners[listener].challengeResponded != undefined){
					listeners[listener].challengeResponded();
				}
			}
		}
		
		//Anti-flood notificateur: Le formulaire de challenge doit-il apparaitre ?
		var antiflood = true;
		that.challengePosted = function(){
			antiflood = false;
			for(var listener in listeners){
				if(listeners[listener].hideForm != undefined){
					listeners[listener].hideForm();
				}
			}
			(function(){ 
				antiflood=true;
				for(var listener in listeners){
					if(listeners[listener].showForm != undefined){
						listeners[listener].showForm();
					}
				}
			}).delay(300);
		}
		that.showForm = function(){
			//@todo: vraie vérification antiflood
			return antiflood;
		}
		
		//Systéme de message
		that.message = function(message){
			for(var listener in listeners){
				if(listeners[listener].newMessage != undefined){
					listeners[listener].newMessage(message);
				}
			}
		}
		
		return that;
	}
	
	chifoumi.pullerInstance = chifoumi.puller();
	
	//Fonction donnant le dom necessaire à une ligne de challenge
	chifoumi.challenge = function(id,bet){
		//Création du dom
		var li = document.createElement("li");
		var li = new Element('li', { 'class': 'chifoumichallenge', 'id': "chifoumi"+id })
		li.innerHTML = "##You have been challenged for## "+ bet +" ##points##<br />"+
					"##Your answer##";
		
		//Pierre		
		var rock = document.createElement("a");
		rock.innerHTML = "##Rock## ";
		rock.observe("click",function(){
			chifoumi.pullerInstance.answerChallenge(id,0);
		});
		li.appendChild(rock);
		
		//Papier
		var paper = document.createElement("a");
		paper.innerHTML = "##Paper## ";
		paper.observe("click",function(){
			chifoumi.pullerInstance.answerChallenge(id,1);
		});
		li.appendChild(paper);
		
		//Ciseaux
		var cissors = document.createElement("a");
		cissors.innerHTML = "##Cissors## ";
		cissors.observe("click",function(){
			chifoumi.pullerInstance.answerChallenge(id,2);
		});
		li.appendChild(cissors);
		
		//J'ai pas envie
		var refuse = document.createElement("a");
		refuse.innerHTML = "##refuse challenge##";
		refuse.observe("click",function(){
			chifoumi.pullerInstance.answerChallenge(id,3);
		});
		li.appendChild(refuse);
		
		//On renvoit le dom créé
		return li;
	}
	
	//Formulaire de challenge
	chifoumi.form = function(){
		var submitcallback = function(){
			if(isNaN(parseInt(bet.value))){
			}
			new Ajax.Request('{/literal}{kurl page="post"}{literal}', 
				{
					method: 'post',
					parameters: "bet="+bet.value+"&weapon="+select.selectedIndex,
					onSuccess: function(transport){
						var response = transport.responseText.evalJSON();
						if(response.posted == 1){
							chifoumi.pullerInstance.message("##Challenged accepted##");
						}
						else{
							chifoumi.pullerInstance.message("##Challenged refused##");
						}
					}
				}
			);
			chifoumi.pullerInstance.challengePosted();
			chifoumi.pullerInstance.message("##Challenged submitted##");
			return false;
		}
		//Création du formulaire
		var dom = document.createElement("form");
		dom.observe("submit",submitcallback);

		//choix de l'arme
		dom.appendChild(document.createTextNode("##Choose your weapon##"));
		var select = document.createElement("select");
		
		var rock = document.createElement("option");
		rock.innerHTML = "##Rock##";
		select.appendChild(rock);
		
		var paper = document.createElement("option");
		paper.innerHTML = "##Paper##";
		select.appendChild(paper);
		
		var cissors = document.createElement("option");
		cissors.innerHTML = "##Cissors##";
		select.appendChild(cissors);
		
		dom.appendChild(select);
		
		//Valeur du pari
		dom.appendChild(document.createTextNode("##Choose your bet##"));
		var bet = document.createElement("input");
		bet.type = "text";
		bet.value = "1000";
		bet.observe("blur",function(){
			if(!isNaN(parseInt(bet.value))){
				if(parseInt(bet.value) > 100000){
					bet.value = 100000;
				}
				else if(parseInt(bet.value) < 1){
					bet.value = 1;
				}
				else{
					bet.value = parseInt(bet.value);
				}
			}
			else{
				bet.value = "1";
			}
		});
		
		dom.appendChild(bet);
		
		//Bouton de confirmation
		var button = document.createElement("input");
		button.type = "button";
		button.value = "##Chalenge##";
		button.observe("click",submitcallback);
		
		dom.appendChild(button);
		
		return dom;
	}
	
	chifoumi.instance = function(container){
		var that = {};
		var dom = container;
		var challengeContainer = document.createElement("ul");
		var formContainer = document.createElement("div");
		var challenge = null;
		var activechallenge = null;
		var messageTitle = document.createElement("h4");
		
		//Challenge en cours
		that.activeChallenge = function(newchallenge){
			if(activechallenge != newchallenge){
				that.newChallenge(newchallenge.id,newchallenge.bet);
				activechallenge = newchallenge;
			}
		}
		
		//Appelé lorsqu'un challenge est répondu
		that.challengeResponded = function(){
			if(challenge != null){
				challengeContainer.removeChild(challenge);
				challenge = null;
			}
			challengeContainer.innerHTML = "##No challenge yet##";
		}
		that.challengeResponded();
		
		//Montre un nouveau challenge
		that.newChallenge = function(id,bet){
			that.challengeResponded();
			challenge = chifoumi.challenge(id,bet);
			challengeContainer.innerHTML = "";
			challengeContainer.appendChild(challenge);
		}
		
		//formulaire
		var form = chifoumi.form();
		that.hideForm = function(){
			formContainer.removeChild(form);
			formContainer.innerHTML = "##You have to wait to challenge again##";
		}
		that.showForm = function(){
			formContainer.innerHTML = "";
			formContainer.appendChild(form);
		}
		
		//Fermeture de l'appli
		that.id = undefined;
		that.close = function(){
			chifoumi.pullerInstance.removeListener(that);
		}
		
		//Messages
		var messagecontainer = document.createElement("ul");
		var messagedelayer = null;
		
		var deletemessage = function(){
			messagecontainer.innerHTML = "##Nothing to say##";
			messageTitle.style.display = "none";
			messagecontainer.style.display = "none";
			messagedelayer = null;
		}
		deletemessage();

		that.newMessage = function(message){
			messagecontainer.innerHTML = message;
			if(messagedelayer != null){
				window.clearTimeout(messagedelayer);
			}
			messageTitle.style.display = "block";
			messagecontainer.style.display = "block";
			messagedelayer = deletemessage.delay(5);
		}
		
		
		
		//On s'abonne aux messages du puller Chifoumi
		if(chifoumi.pullerInstance == undefined){
			chifoumi.pullerInstance = chifoumi.puller();
		}
		chifoumi.pullerInstance.addListener(that);
		
		var  title = null;
		
		//On ajoute la liste des défis
		title = document.createElement("h4");
		title.innerHTML = "##Challenge##";
		container.appendChild(title);
		container.appendChild(challengeContainer);
		
		//Formulaire d'ajout de défi
		title = document.createElement("h4");
		title.innerHTML = "##Challenge Someone##";
		container.appendChild(title);
		container.appendChild(formContainer);
		if(chifoumi.pullerInstance.showForm()){
			that.showForm();
		}
		else{
			formContainer.innerHTML = "##You have to wait to challenge again##";
		}
		
		//On ajout le conteneur à messages
		
		messageTitle.innerHTML = "##Message##";
		container.appendChild(messageTitle);
		container.appendChild(messagecontainer);
		
		return that;
	}
}

//Karibou 2.5 Javascript
var chifoumiClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		this.instance = chifoumi.instance(getSubElementById("chifoumi",container));
	},
	refresh: function(){
		if(this.instance.refresh != undefined){
			this.instance.refresh();
		}
	},
	onClose: function(){
		this.instance.close();
		//delete this.instance;
		/*if(chifoumi.pullerInstance.listenercount <= 0){
			delete chifoumi;
		}*/
	},
	onShade: function(){
		
	}
});



// </script>
// {/literal}