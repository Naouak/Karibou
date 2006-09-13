<?
	$lang = $this->vars['myNetCVLanguage'];
	if (isset($this->vars['groups']) && count($this->vars['groups'])>0)
	{
		echo "<h3>NetCV : ".count($this->vars['groups']).' Curriculum Vitae</h3>';
		/*
		if (count($this->vars['groups'])>1) 
			echo _('NETCV_CVs');
		else
			echo _('NETCV_CV');
		echo " :";
		*/
		echo '<ul>';
		foreach($this->vars['groups'] as $group)
		{
			if ($group->getInfo('diffusion') != 'private')
			{
				echo '<li>';
				echo '<a href="http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'">http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'</a>';
				$cvlist = $group->returnCVList();
				if (count($cvlist)>0)
				{
					echo " (";
					$n = 0;
					foreach($cvlist as $cv)
					{
						if ($n > 0)
							echo ', ';
						echo '<a href="http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'/'.$cv->getInfo('lang').'" title="http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'/'.$cv->getInfo('lang').'">'.$lang->getNameByCode($cv->getInfo('lang')).'</a>';
						$n++;
					}
					echo ")";
				}
				echo '</li>';
			}
		}
		echo '</ul>';
		
	}
?>