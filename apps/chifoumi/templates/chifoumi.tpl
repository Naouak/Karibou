{literal}
<style type="text/css">
	.chifoumierror {
		color: red; 
	}
	
	#chifoumimenu{
		width: 100%;
		border-bottom: solid black 1px;
		margin-left: 0px;
		font-size: larger;
	}
	
	#chifoumimenu li{
		display: inline-block;
		list-style: none;
		background: RGB(200,200,200);
		padding: 3px;
	}
	
	#chifoumimenu li a{
		color: black;
	}
	
	#chifoumi_content h4{
		width: 100%;
		background: RGB(200,200,200);
		color: black;
		margin-left: 0px;
		margin-top: 0px;
		font-size: larger;
	}
	
	.chifoumilabel{
		display: block;
		font-decoration: italic;
	}
</style>
{/literal}
<div id="chifoumi_content">
	<ul id="chifoumimenu">
		<li><a onclick="ChifoumiCont.toChallenge();">##Challenges##</a></li>
		<li><a onclick="ChifoumiCont.toSetChallenge();">##Set a Challenge##</a></li>
	</ul>
	<div id="chifoumiChallengeform">
	<h4>##Set a challenge##</h4>
	<form id="chifoumiform">
		<label><span class="chifoumilabel">##Choose Your Weapon##</span>
		<select id="chifoumiweapon">
			<option>##Rock##</option>
			<option>##Paper##</option>
			<option>##Cissors##</option>
		</select></label>
		<br />
		<label><span class="chifoumilabel">##Choose Your Bet##</span>
		<input type="text" name="score" id="chifoumibet" value="1000"/>
		</label>
		<br />
		<input type="submit"  value="##Challenge Someone##"/>
	</form>
	</div>
	<div id="chifoumiChallengeList">
	<h4>##Challenges##</h4>
	<ul id="chifoumichallenges">
		
	</ul>
	</div>
	<h4>##Message##</h4>
	<div id="ChifoumiMessage">
		
	</div>
	
	
	
</div>