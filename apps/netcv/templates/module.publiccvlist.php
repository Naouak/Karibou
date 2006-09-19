<?
	$lang = $this->vars['myNetCVLanguage'];
	if (isset($this->vars['groups']) && count($this->vars['groups'])>0)
	{
		$r = '';
		foreach($this->vars['groups'] as $group)
		{
			if ($group->getInfo('diffusion') != 'private')
			{
				$r .= '<li>';
				$r .= '<a href="http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'">http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'</a>';
				$cvlist = $group->returnCVList();
				if (count($cvlist)>0)
				{
					$r .= " (";
					$n = 0;
					foreach($cvlist as $cv)
					{
						if ($n > 0)
							$r .= ', ';
						$r .= '<a href="http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'/'.$cv->getInfo('lang').'" title="http://'.$group->getInfo('hostname').'.'.$GLOBALS['config']['netcv']['host'].'/'.$cv->getInfo('lang').'">'.$lang->getNameByCode($cv->getInfo('lang')).'</a>';
						$n++;
					}
					$r .= ")";
				}
				$r .= '</li>';
			}
		}		
	}
	
	if (isset($r) && count($r)>0)
	{
		echo "<h3>NetCV : ".count($this->vars['groups']).' Curriculum Vitae</h3>';
		echo '<ul>';
		echo $r;
		echo '</ul>';
	}
?>