<h1>Photos</h1>
<p></p>

<?
	if (isset($this->vars['photos']) && count($this->vars['photos'])>0)
	{
		foreach($this->vars['photos'] as $photo)
		{
			echo '<a href="/pub/photos/'.$this->vars['path'].'/big/'.$photo.'"><img src="/pub/photos/'.$this->vars['path'].'/small/'.$photo.'" border="0"></a>';
		}
	}
	else
	{
		echo _('PHOTOSVIEWER_NOPHOTO');
	}
?>