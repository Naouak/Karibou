<div class="home">

	<script type="text/javascript" language="javascript">

	// <![CDATA[
	
	var save_url = '{kurl action="saveapps"}';
	var delete_url = '{kurl action="deleteapps"}';
	
	var containers = [
			"default_container" ,
	{foreach name=c1 key=key item=c from=$containers}
			{if ! $smarty.foreach.c1.first},  {/if}"{$key}"
	{/foreach}
			] ;
	
	var accept_small = ['sbox'];
	var accept_medium = ['sbox', 'mbox'];
	var accept_large = ['sbox', 'mbox', 'lbox'];
	
	function getSerializedData()
	{ldelim}
		return KSortable.serialize('default_container'){foreach key=key item=c from=$containers}+"&"+Sortable.serialize('{$key}'){/foreach} ;
	{rdelim}
	
	function createSortable()
	{ldelim}
	{foreach key=key item=c from=$containers}
		KSortable.create("{$key}" ,
		{ldelim}
			dropOnEmpty:true,
			tag:'div',
			overlap:'horizontal',
			handle: 'handle',
			constraint: false,
			containment: containers ,
	{if $c=="s"}
			accept: accept_small,
	{elseif $c=="m"}
			accept: accept_medium,
	{elseif $c=="l"}
			accept: accept_large,
	{/if}
			onUpdate:function()
			{ldelim}
				new Ajax.Request(save_url,
					{ldelim}
					asynchronous: true , 
					method: 'post' , 
					postBody: getSerializedData()
					{rdelim}
				);
			{rdelim}		
		{rdelim}
		) ;
	{/foreach}
		
		KSortable.create("default_container" ,
		{ldelim}
			tag:'div',
			overlap:'horizontal',
			handle: 'handle',
			constraint: false,
			containment: containers ,
			onUpdate:function()
			{ldelim}
				new Ajax.Request(save_url,
					{ldelim}
					asynchronous: true , 
					method: 'post' , 
					postBody: getSerializedData()
					{rdelim}
				);
			{rdelim}
		{rdelim}
		) ;
		
		
	{rdelim}
	
	
	var handlerFunc = function(t)
	{ldelim}
	    createSortable();
	{rdelim}
	
	var insertFunc = function(id, content)
	{ldelim}
	    div = $(id);
	    div.innerHTML += content;
	{rdelim}
	
	function submit_form(form_id, content_id)
	{ldelim}
		var f = document.getElementById(form_id);
		inputList = f.getElementsByTagName('input');
		var queryComponents = new Array();
		for( i=0 ; i < inputList.length ; i++ )
		{ldelim}
			myInput = inputList.item(i);
			if( myInput.type == 'file' ) return true;
			if( myInput.name )
			{ldelim}
				queryComponents.push(
	        	  encodeURIComponent(myInput.name) + "=" + 
	        	  encodeURIComponent(myInput.value) );
			{rdelim}
		{rdelim}
	
		areaList = f.getElementsByTagName('textarea');
		for( i=0 ; i < areaList.length ; i++ )
		{ldelim}
			myArea = areaList.item(i);
			if( myArea.name )
			{ldelim}
				queryComponents.push(
	        	  encodeURIComponent(myArea.name) + "=" + 
	        	  encodeURIComponent(myArea.value) );
			{rdelim}
		{rdelim}
	
		selectList = f.getElementsByTagName('select');
		for( i=0 ; i < selectList.length ; i++ )
		{ldelim}
			mySelect = selectList.item(i);
			if( mySelect.name )
			{ldelim}
				queryComponents.push(
	        	  encodeURIComponent(mySelect.name) + "=" + 
	        	  encodeURIComponent(mySelect.value) );
	       	{rdelim}
		{rdelim}
	
	
		var post_vars = queryComponents.join("&");
	
		new Ajax.Updater(content_id, '{kurl page="miniappconfigajax"}', {ldelim}
				asynchronous:true,
				evalScripts:true,
				method:'post',
				postBody:post_vars
			{rdelim});
		return false;
	{rdelim}
	
    Event.observe(window, "load", function() {ldelim}
        createSortable();
    {rdelim});
	// ]]>
	</script>
	
	<!--<h1>##HEADER_PAGE_TITLE##</h1>-->
	<a id="personalise_button" href="#" onclick="return editHomeApps('{"##DEFAULT_END_EDIT##"|escape:'quotes'}', '{"##DEFAULT_EDIT##"|escape:'quotes'}');">##DEFAULT_EDIT##</a>
	<br class="spacer" />

	<div id="default_page_configbar" style="display: none">
		<div class="configbar" >
			##CHOOSECONTAINER##
			<a href="{kurl action='setcontainers'}?size=sms"><img src="themes/default/images/home/sms.png" alt="Small Medium Small" /></a> 
			<a href="{kurl action='setcontainers'}?size=ssm"><img src="themes/default/images/home/ssm.png" alt="Small Small Medium" /></a> 
			<a href="{kurl action='setcontainers'}?size=mss"><img src="themes/default/images/home/mss.png" alt="Medium Small Small" /></a> 
			<a href="{kurl action='setcontainers'}?size=mm"><img src="themes/default/images/home/mm.png" alt="Medium Medium" /></a> 
			<a href="{kurl action='setcontainers'}?size=ssss"><img src="themes/default/images/home/ssss.png" alt="Small Small Small Small" /></a>
			<a href="{kurl action='setcontainers'}?size=sl"><img src="themes/default/images/home/sl.png" alt="Small Large" /></a> 
			<a href="{kurl action='setcontainers'}?size=ls"><img src="themes/default/images/home/ls.png" alt="Large Small" /></a>
		</div>
	
		<div class="configbar" >
			##ADDMINIAPP##
			{foreach item=app from=$miniapps}
				<a href="{kurl action='addapp'}?app={$app.id}" onclick="add_application('{kurl page='miniappaddajax' miniapp=$app.id}'); return false;">{translate key=$app.id}</a> |
			{/foreach}
		</div>
	</div>

	<div id="default_container" class="default_container" >
	{hook name="default_container"}
	</div>
	<br class="spacer" />
	
	{foreach key=key item=c from=$containers}
	<div id="{$key}" class="cont_{$c}col left view_{$c}" >
	{hook name=$key}
	</div>
	{/foreach}
	<br class="spacer" />
</div>
