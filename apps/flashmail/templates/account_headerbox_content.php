	<li id="account_flashmail_headerbox_sentence" class="flashmail">
	<?
	if ( isset($this->vars['flashmails']) && ($this->vars['flashmails'] !== FALSE) && (count($this->vars['flashmails']) >= 1))
	{
	?>
		<a href="<?=kurl(array('app'=>"flashmail", 'page'=>"unreadlist"));?>" onclick="flashmail_blinddown('flashmail_headerbox_unreadlist'); return false;"><?=count($this->vars['flashmails']);?>
		<?
		if (count($this->vars['flashmails']) > 1)
		{
			echo _('NEW_FLASHMAILS');
		}
		else
		{
			echo _('NEW_FLASHMAIL'); 
		} ?></a>
	<?
	}
	?>
	</li>
	
	<div id="flashmail_headerbox_unreadlist_TMP" style="visibility: hidden; display: none;">
	<?
	if (count($this->vars['flashmails']) > 1)
	{
		include('list.php');
	}
	?>
	</div>