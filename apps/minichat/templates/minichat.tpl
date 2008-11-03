	<script type="text/javascript" language="javascript">
	// <![CDATA[

{literal}
	function submit_mc_form(f)
	{
		var f = document.getElementById(form_id);
		inputList = f.getElementsByTagName('input');
		var queryComponents = new Array();
		for( i=0 ; i < inputList.length ; i++ )
		{
			myInput = inputList.item(i);
			if( myInput.type == 'file' ) return true;
			if( myInput.name )
			{
				queryComponents.push(
	        	  encodeURIComponent(myInput.name) + "=" +
	        	  encodeURIComponent(myInput.value) );
	        	 
	        	myInput.value = "";
			}
		}

		var post_vars = queryComponents.join("&");

		new Ajax.Request('{/literal}{kurl action="post"}{literal}', {
				asynchronous:true,
				evalScripts:true,
				method:'post',
				postBody:post_vars
			});

		return false;
	}
{/literal}
	// ]]>
	</script>


<h1>Minichat</h1>
<div class="minichat">
	
	<div id="minichat_live">
        {include file="content.tpl" max=$config.max.normal}
	</div>

    {if $permission > _READ_ONLY_}
    
    <form autocomplete="off" action="{kurl action="post"}" method="post" id="minichat_live_form" onsubmit="return submit_mc_form(this);">
        <input type="text" name="post" size="40" id="message" />
        <input type="submit" value="##MINICHAT_SEND##" class="button" />
    </form>
    {/if}
    <br /><br />
    <h2>##CHOOSE_DATE##</h2>
        <br />
        <div id="track1" style="width:90%;background-color:#aaa;height:5px;">
            <div id="handle1" style="width:5px;height:10px;background-color:#f00;cursor:move;"> </div>
        </div>
        <a id="sliderDate" href="minichat/day-{$maxDate}">##TODAY##</a>
{literal}
<script>
    var slider = new Control.Slider('handle1','track1', {minimum:{/literal}{$minDate}, maximum:{$maxDate}{literal}, increment:86400000, alignX: -5, alignY: -5});
    var delta = {/literal}{$minDate} - {$maxDate}; {literal}
    slider.options.onChange = function (value) {
        date = new Date(1000 * (value * delta + {/literal}{$maxDate}{literal}));
        document.getElementById("sliderDate").innerHTML = date.toLocaleDateString();
    };
    slider.options.onSlide = function (value) {
        date = new Date(1000 * (value * delta + {/literal}{$maxDate}{literal}));
        document.getElementById("sliderDate").innerHTML = date.toLocaleDateString();
        {/literal}
        document.getElementById("sliderDate").href = "minichat/day-" + parseInt(value * delta + {$maxDate});
        {literal}
    };
</script>
{/literal}
</div>
