<h3 class="handle">##WEATHER## {$city}</h3>
<center>
{section name=i loop=$day_name step=1}
	<h4>{$day_name[i]}</h4>
	<img src="{$image[i]}" alt="{$alternatif[i]}"><br />
	{$low_temp[i]}°C / {$high_temp[i]} °C

{/section}
</center>