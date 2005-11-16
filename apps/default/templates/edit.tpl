<div class="home">
	<script type="text/javascript" language="javascript">
	// <![CDATA[
	
	var save_url = '{kurl action="saveapps"}';
	var delete_url = '{kurl action="deleteapps"}';
	
	var containers = [
			"default_container" ,
			"delete_container" ,
	{foreach name=c key=key item=c from=$containers}
			{if ! $smarty.foreach.c.first},  {/if}"{$key}"
	{/foreach}
			] ;
	
	var accept_small = ['sbox'];
	var accept_medium = ['sbox', 'mbox'];
	var accept_large = ['sbox', 'mbox', 'lbox'];
	
	function getSerializedData()
	{ldelim}
		return KSortable.serialize('default_container'){foreach name=c key=key item=c from=$containers}+"&"+Sortable.serialize('{$key}'){/foreach} ;
	{rdelim}
	
	function getSerializedDeleteData()
	{ldelim}
		return KSortable.serialize('delete_container') ;
	{rdelim}
	
	function createSortable()
	{ldelim}
	{foreach name=container key=key item=container from=$containers}
		KSortable.create("{$key}" ,
		{ldelim}
			dropOnEmpty:true,
			tag:'div',
			overlap:'horizontal',
			handle:'handle',
			constraint: false,
			containment: containers ,
	{if $container=="s"}
			accept: accept_small,
	{elseif $container=="m"}
			accept: accept_medium,
	{elseif $container=="l"}
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
			handle:'handle',
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
		
		KSortable.create("delete_container" ,
		{ldelim}
			dropOnEmpty:true,
			tag:'div',
			overlap:'horizontal',
			handle:'handle',
			constraint: false,
			containment: containers,
			onUpdate:function()
			{ldelim}
				new Ajax.Request(delete_url,
					{ldelim}
					asynchronous: true ,
					method: 'post' ,
					postBody: getSerializedDeleteData()
					{rdelim}
				);
				KSortable.dropAll("delete_container");
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
	
	// ]]>
	</script>
	
	<a  id="personalise_button" href="{kurl app=''}">##BACKHOME##</a>
	<h1>##HEADER_PAGE_TITLE##</h1>
	<br class="spacer" />
	{hook name="html_head"}
	
	<div class="configbar" >
	##CHOOSECONTAINER##
	<a href="{kurl action='setcontainers'}?size=sms"><img src="/themes/default/images/home/sms.png" alt="Small Medium Small" /></a> 
	<a href="{kurl action='setcontainers'}?size=sl"><img src="/themes/default/images/home/sl.png" alt="Small Large" /></a> 
	<a href="{kurl action='setcontainers'}?size=ls"><img src="/themes/default/images/home/ls.png" alt="Large Small" /></a> 
	<a href="{kurl action='setcontainers'}?size=mm"><img src="/themes/default/images/home/mm.png" alt="Medium Medium" /></a> 
	<a href="{kurl action='setcontainers'}?size=ssss"><img src="/themes/default/images/home/ssss.png" alt="Small Small Small Small" /></a>
	</div>
	
	<div class="configbar" >
		##ADDMINIAPP##
	{foreach item=app from=$miniapps}
		<a href="{kurl action='addapp'}?app={$app.id}" onclick="new Ajax.Updater('default_container', '{kurl page='miniappaddajax' miniapp=$app.id}', {ldelim}asynchronous:true, evalScripts:true, onComplete:handlerFunc, insertion:insertFunc {rdelim}); return false;">{translate key=$app.id}</a> |
	{/foreach}
	</div>
		
	<div id="delete_container" class="delete_container left" >
	<span class="background">##TRASH##</span>
	</div>
	
	<div id="default_container" class="default_container left" >
	{hook name="default_container"}
	</div>
	
	<br class="spacer" />
	
	{foreach key=key item=size from=$containers}
	<div id="{$key}" class="cont_{$size}col left view" >
	{hook name=$key}
	</div>
	{/foreach}
	
	<br class="spacer" />
	
	<script>
		createSortable();
	</script>
	
	{*
					    onSuccess: function(t)
					    {ldelim}
					        alert(t.responseText);
					    {rdelim},
					    // Handle 404
					    on404: function(t) {
					        alert('Error 404: location "' + t.statusText + '" was not found.');
					    },
					    // Handle other errors
					    onFailure: function(t) {
					        alert('Error ' + t.status + ' -- ' + t.statusText);
					    }
	*}
</div>