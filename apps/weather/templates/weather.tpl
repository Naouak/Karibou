<h3 class="handle">##WEATHER## {$city}</h3>
<div class="directory">
{section name=i loop=$day_name step=1}
	<div style="float:left; border:solid 1px #ACACAC; text-align:center; margin:1px; min-width:120px; min-height:100px;" >
		<span style="font-weight:bold;">{$day_name[i]}</span><br />
		<img src="{$image[i]}" alt="{$alternatif[i]}"><br />
		{$low_temp[i]}°C / {$high_temp[i]} °C
	</div>
{/section}
</div>