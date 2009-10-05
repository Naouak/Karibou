// {literal}
// <script>

//Conteneur pour l'applciation Chifoumi
var ChifoumiCont = {};

//Objet de base avec des fonctions utiles
ChifoumiCont.BaseObj = function(){
	var that = {
		args: {}
	};
	
	//Création automatique des arguments à passer en Ajax
	that.parseArgs = function(){
		var str = "";
		if(that.args != undefined){
			for(var arg in that.args){
				str+=arg+"="+encodeURI(that.args[arg])+"&";
			}
		}
		return str;
	};
	
	//Envoi d'une request Ajax
	that.AjaxRequest = function(page){
		new Ajax.Request('{/literal}{kurl page="'+page+'"}{literal}', 
			{
				method: 'post',
				parameters: that.parseArgs(),
			}
		);
		return false;
	};
	
	//Message Chifoumi
	//Mise en place du conteneur à message
	that.SetMessageContainer = function(id){
		that.messageContainer = $(id);
		that.ul = document.createElement("ul");
		that.messageContainer.appendChild(that.ul);
		/*if(that.ul.innerHTML == ""){
			that.ul.innerHTML = "##No messages##";
		}*/
	}
	
	//Ajout d'un message
	that.Message = function(msg){
		var subthat = {};
		
		subthat.node = document.createElement("li");
		subthat.node.innerHTML = msg;
				
		that.ul.appendChild(subthat.node);
		
		subthat.Destroy = function(){
			window.clearTimeout(subthat.timerID);
			that.ul.removeChild(subthat.node);
		};
		
		subthat.ChangeMessage = function(msg){
			subthat.node.innerHTML = msg;
		};
		
		subthat.ChangeDelay = function(time){
			window.clearTimeout(subthat.timerID);
			subthat.timerID = subthat.Destroy.delay(time);
		}
		
		subthat.timerID = subthat.Destroy.delay(10);
		
		return subthat;
	}
	
	return that;
}

//Objet qui se charge de gérer le formulaire de lancement de défi de Chifoumi
ChifoumiCont.Form = function(){
	var that = ChifoumiCont.BaseObj();
	that.SetMessageContainer("ChifoumiMessage");
	
	//Chargement du formulaire
	that.loadForm = function(){
		that.args.bet = $("chifoumibet").value;
		that.args.weapon = $("chifoumiweapon").selectedIndex;
	};
	
	//Vérification du formulaire
	that.check = function(){	
		if(parseInt(that.args.bet) != parseInt(that.args.bet) || parseInt(that.args.bet) < 0 || parseInt(that.args.bet) > 100000){
			that.error = "##Bet must be between 0 and 100000##";						
		}
	};

	//Envoi des données si valables
	that.send = function(){
		if(that.error != undefined){
			that.Message("<span class='chifoumierror'>"+that.error+"</span>");
			return false;
		}
		var msg = that.Message("##Sending bet##");
		that.AjaxRequest("post");
		msg.Destroy();
		that.Message(new Date().toLocaleString()+":<br /> ##Bet sent##");
		return false;
	};
	return that;
};

//Objet caractérisant une ligne de challenge
ChifoumiCont.ChallengeLine = function(container,id,bet){
	var that = ChifoumiCont.BaseObj();
	
	var li = document.createElement("li");
	var li = new Element('li', { 'class': 'chifoumichallenge', 'id': "chifoumi"+id })
	li.innerHTML = "##You have been challenged for## "+ bet +" ##points##<br />"+
				"##Your answer##";
				
	var rock = document.createElement("a");
	rock.innerHTML = "##Rock## ";
	rock.observe("click",function(){ChifoumiCont.ChallengeResponse(id,0);that.Destroy();});
	li.appendChild(rock);
	
	var paper = document.createElement("a");
	paper.innerHTML = "##Paper## ";
	paper.observe("click",function(){ChifoumiCont.ChallengeResponse(id,1);that.Destroy();});
	li.appendChild(paper);
	
	var cissors = document.createElement("a");
	cissors.innerHTML = "##Cissors## ";
	cissors.observe("click",function(){ChifoumiCont.ChallengeResponse(id,2);that.Destroy();});
	li.appendChild(cissors);
	
	var refuse = document.createElement("a");
	refuse.innerHTML = "##refuse challenge##";
	refuse.observe("click",function(){ChifoumiCont.ChallengeResponse(id,3);that.Destroy();});
	li.appendChild(refuse);

	container.appendChild(li);
	
	//Fonction de Destruction de la ligne
	that.Destroy = function(){
		container.removeChild(li);
	}
	
	return that;
}
	
//Gestionnaire des challenge, va chercher les challenges	
ChifoumiCont.Challenge = function(){
	var that = ChifoumiCont.BaseObj();
	that.linelist = Array();
	
	that.SetMessageContainer("ChifoumiMessage");
	
	that.updateChallengeList = function(){
		new Ajax.Request('{/literal}{kurl page="challenge"}{literal}', 
			{
				method: 'post',
				parameters: that.parseArgs(),
				onSuccess: function(transport){
					var response = JSON.parse(transport.responseText);
					var challenges = $("chifoumichallenges").childNodes;
					for(var j=0; j < challenges.length; j++){
							$("chifoumichallenges").removeChild(challenges[j]);
					}
					for(var i =0; i < response.length;i++){
						if($("chifoumi"+response[i].id) == undefined){
							ChifoumiCont.ChallengeLine($("chifoumichallenges"),response[i].id,response[i].bet);
						}
					}
					
					if(response.length == 0){
						$("chifoumichallenges").innerHTML = "##No challenges yet##";
					}
				}
			}
		);
		return false;
	};
	new PeriodicalExecuter(that.updateChallengeList, 1);
	
	return that;
}

//Quand on répond à un challenge			
ChifoumiCont.ChallengeResponse = function(id,weapon){
	var that = ChifoumiCont.BaseObj();
	that.args.id = id;
	that.args.weapon = weapon;
	
	that.AjaxRequest("challenge");
	
	return that;
};

ChifoumiCont.toChallenge = function(){
	$("chifoumiChallengeform").hide();
	$("chifoumiChallengeList").show();
};

ChifoumiCont.toSetChallenge = function(){
	$("chifoumiChallengeform").show();
	$("chifoumiChallengeList").hide();
};

//Karibou 2.5 Javascript
var chifoumiClass = Class.create(KApp, {
	initialize: function ($super, appName, id, container, karibou) {
		$super(appName, id, container, karibou);
		
		var updater = ChifoumiCont.Challenge();
		ChifoumiCont.toChallenge();
	},
	//Quand on lance un pari
	on_chifoumiform_submit: function () {
			//Si vous cherchez à comprendre, ça sert juste à protêger la portée(paranoia quand tu nous tiens).
			return (function(){
						var form = ChifoumiCont.Form();
						form.loadForm();
						form.check();
						return form.send();
					})();
	}
});



// </script>
// {/literal}