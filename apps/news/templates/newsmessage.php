<?
//{section name=m loop=$theNewsMessages step=1}

if ( (count($this->vars['theNewsMessages'])>0) && ($this->vars['theNewsMessages'] !== FALSE) )
{
	foreach ($this->vars['theNewsMessages'] as $key => $message)
	{
		if ($message[0] < 2)
		{
		echo '<div class="success">';
			echo $message[1];
		echo '</div>';
		} else {
		echo '<div class="error">';
			echo $message[1];
		echo '</div>';
		}
	}
}
//{/section}
?>