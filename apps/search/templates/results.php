<?
if (isset($this->vars['keywords']))
	$keywords = $this->vars['keywords'];
	
if (isset($this->vars['app']))
	$app = $this->vars['app'];
else
	$app = 'everywhere';
?>
<div class="search">
	<h1><?=_('SEARCH');?></h1>
	<form method="post">
		<input type="text" name="keywords" value="<? if (isset($keywords)) echo $keywords; ?>">
		<?=_('SEARCH_IN');?>
		<select name="app">
			<option value="everywhere"<?if ($app == 'everywhere') echo " SELECTED"?>><?=_('INTRANET');?></option>
			<option value="news"<?if ($app == 'news') echo "SELECTED"?>><?=_('APP_NEWS');?></option>
			<option value="fileshare"<?if ($app == 'fileshare') echo "SELECTED"?>><?=_('APP_FILESHARE');?></option>
			<option value="calendar"<?if ($app == 'calendar') echo "SELECTED"?>><?=_('APP_CALENDAR');?></option>
			<option value="annuaire"<?if ($app == 'annuaire') echo "SELECTED"?>><?=_('APP_ANNUAIRE');?></option>
		</select>
		<input type="submit" value="<?=_('SEARCH_TITLE');?>">
	</form>

<?
if ( (isset($this->vars['articles']) && count($this->vars['articles']) > 0) ||
	  (isset($this->vars['files']) && count($this->vars['files']) > 0) ||
	(isset($this->vars['usersfound']) && count($this->vars['usersfound']) > 0) ||
	  (isset($this->vars['events']) && $this->vars['events']->count() > 0))
{

	echo '<div class="goto">';
	echo _('SEARCH_GOTO').' :';

	if (isset($this->vars['articles']) && count($this->vars['articles']) > 0)
	{
		echo '<a href="'.kurl(array('app'=>'search')).'#search_news">'.count($this->vars['articles']).' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' '._('APP_NEWS').'</a>';
	}
	
	if (isset($this->vars['files']) && count($this->vars['files']) > 0)
	{
		echo '<a href="'.kurl(array('app'=>'search')).'#search_fileshare">'.count($this->vars['files']).' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' '._('APP_FILESHARE').'</a>';
	}
	
	if (isset($this->vars['events']) && $this->vars['events']->count() > 0)
	{
		echo '<a href="'.kurl(array('app'=>'search')).'#search_calendar">'.$this->vars['events']->count().' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' '._('APP_CALENDAR').'</a>';
	}

	if (isset($this->vars['annuaire']) && $this->vars['annuaire']->count() > 0)
	{
		echo '<a href="'.kurl(array('app'=>'search')).'#search_annuaire">'.$this->vars['usersfound']->count().' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' '._('APP_ANNUAIRE').'</a>';
	}
	
	echo '</div>';
}
?>
	<br style="clear: both;" />
<?
if (isset($this->vars['errors']) && count($this->vars['errors']) > 0)
{
	/*##SEARCH_RESULTSS## ##FOR##*} <strong>{$keywords}</strong>*/
	echo '<ul style="color: #900; list-style: none;">';
		foreach ($this->vars['errors'] as $error)
		{
			echo '<li>'.$error.'</li>';
		}
	echo '</ul>';
}
else
{
	//echo '<h1>'._('SEARCH_SEARCHRESULTS').'</h1>';
	//echo '<div>'.ucfirst(_('SEARCH_RESULTSS')).' '._('FOR').' <strong>'.$keywords.'</strong></div>';
	if (!isset($this->vars['articles']))
	{
	}
	elseif (count($this->vars['articles'])== 0)
	{
		echo '<div id="search_news" class="resultset">';
		echo _('SEARCH_NORESULT').' '._('SEARCH_IN').' <strong>'._('NEWS').'</strong>';
		echo "</div>";
	}
	else
	{
		echo '<div id="search_news" class="resultset">';
		echo count($this->vars['articles']).' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' <strong>'._('NEWS').'</strong>';
		echo '<ul class="articles">';
		foreach ($this->vars['articles'] as $article)
		{
			echo '<li>';
				echo '<div class="title"><a href="'.kurl(array('app'=>"news", 'page'=>"view", 'id' => $article->getID(), 'displayComments'=>'1')).'">'.highlight($article->getTitle(), $keywords).'</a></div>';
				echo '<div class="content">'.find_and_highlight($article->getContent(), $keywords).'</div>';
				echo '<div class="url">'.$GLOBALS['config']['site']['base_url'].kurl(array('app'=>"news", 'page'=>"view", 'id' => $article->getID(), 'displayComments'=>'1')).'</div>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
		
	}
	
	if (!isset($this->vars['files']))
	{
	}
	elseif (count($this->vars['files'])== 0)
	{
		echo '<div id="search_fileshare" class="resultset">';
		echo _('SEARCH_NORESULT').' '._('SEARCH_IN').' <strong>'._('FILESHARE').'</strong>';
		echo "</div>";
	}
	else
	{
		echo '<div id="search_fileshare" class="resultset">';
		echo count($this->vars['files']).' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' <strong>'._('FILESHARE').'</strong>';
		echo '<ul class="files">';
		foreach ($this->vars['files'] as $file)
		{
			echo '<li>';
				echo '<div class="title"><a href="'.kurl(array('app' => 'fileshare', 'page'=>"details", 'elementpath'=>$file->getPathBase64())).'">'.highlight($file->getName(), $keywords).'</a></div>';
				echo '<div class="content">'.find_and_highlight($file->getLastVersionInfo('description'), $keywords).'</div>';
				echo '<div class="url">'.$GLOBALS['config']['site']['base_url'].kurl(array('app' => 'fileshare', 'page'=>"details", 'elementpath'=>$file->getPathBase64())).'</div>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
		
	}





	
	if (!isset($this->vars['events']))
	{
	}
	elseif ($this->vars['events']->count()== 0)
	{
		echo '<div id="search_calendar" class="resultset">';
		echo _('SEARCH_NORESULT').' '._('SEARCH_IN').' <strong>'._('CALENDAR').'</strong>';
		echo "</div>";
	}
	else
	{
		$this->vars['events']->sort(array("ObjectList","compare_date"));

		echo '<div id="search_calendar" class="resultset">';
		echo $this->vars['events']->count().' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' <strong>'._('CALENDAR').'</strong>';
		echo '<ul class="files">';
		$previousdate = '';
		foreach ($this->vars['events'] as $event)
		{
			$currentdate = $event->o_start->getDate("%d-%m-%Y");

			if (($previousdate !== $currentdate) && ($previousdate !== '') )
			{
				echo " </ul>\n";
				echo "</li>\n";
			}
			
			if ($previousdate !== $currentdate)
			{
				//Nouvelle ligne
				echo "\n<li><div class=\"title\"><a href=\"".kurl(array('app' => 'calendar', 'page' => 'view', 'cal_id' => $event->calendarid, 'year' => $event->o_start->getYear(), 'month' => $event->o_start->getMonth(), 'day' => $event->o_start->getDay()))."\">".$event->o_start->getDate("%d %B %Y")."</a></div>\n";
				echo " <ul>\n";
			}
echo '<li><div class="colorsquare" style="margin: 2px;background-color: #'.$event->getCalendarColor().';"></div>&nbsp;<strong style="color: #787878;">'.$event->o_start->getDate("%H:%M").'</strong> : ';
				echo '<span>'.highlight($event->summary, $keywords).'</span>';
				
				if ($event->description !== '')
					echo '<span> ('.highlight($event->description, $keywords).')</span>';
				echo '</li>';
				
			/*
			echo '<div class="title"><a href="'.kurl(array('app' => 'fileshare', 'page'=>"details", 'elementpath'=>$file->getPathBase64())).'">'.highlight($file->getName(), $keywords).'</a></div>';
				echo '<div class="content">'.find_and_highlight($file->getLastVersionInfo('description'), $keywords).'</div>';
				echo '<div class="url">'.$GLOBALS['config']['site']['base_url'].kurl(array('app' => 'fileshare', 'page'=>"details", 'elementpath'=>$file->getPathBase64())).'</div>';
				*/
			$previousdate = $currentdate;
		}
		echo '</ul>';
		echo '</div>';
		
	}


if (!isset($this->vars['usersfound']))
	{
	}
	elseif (count($this->vars['usersfound'])== 0)
	{
		echo '<div id="search_annuaire" class="resultset">';
		echo _('SEARCH_NORESULT').' '._('SEARCH_IN').' <strong>'._('ANNUAIRE').'</strong>';
		echo "</div>";
	}
	else
	{
		echo '<div id="search_annuaire" class="resultset">';
		echo count($this->vars['usersfound']).' '._('SEARCH_RESULTSS').' '._('SEARCH_IN').' <strong>'._('ANNUAIRE').'</strong>';
		echo '<div class="directory search">';//<ul class="usersfound">
		foreach ($this->vars['usersfound'] as $user)
		{
			echo '
			<div class="thumbnail">
			<div class="image">
				<a href="annuaire/'.$user->getLogin().'"  title="'.$user->getFirstName().$user->getSurname().'"><img src="'.$user->getPicturePath().'" /></a>
			</div>
			<div class="title">
				<a href="annuaire/'.$user->getLogin().'" title="'.$user->getFirstName().$user->getSurname().'">';

			//Nickname, Name, login
			if($user->getSurname()) echo $user->getSurname();
			elseif($user->getFirstName()) echo $user->getFirstname().' '.$user->getLastname();
			else echo $user->getLogin();

			echo '</a>
			</div>
			</div>
			';


		}


		


		echo '</div>';//</ul>
		echo '</div>';
		
	}




}
?>
</div>
<style>
	#container div.search {
		font-size: 0.9em;
	}
	#container div.search ul {
		list-style: none;
	}
	#container div.search ul li {
		margin-top: 5px;
		margin-bottom: 15px;
	}
	#container div.search ul li div.url{
		color: #008000;
		width: 90%;
		overflow: hidden;
		white-space: nowrap;
	}
	#container div.search ul li div.title, #container div.search div.goto a, #container div.search div.goto a:visited {
		font-size: 1.25em;
	}
	#container div.search div.goto a, #container div.search div.goto a:visited {
		font-weight: bold;
		color: #4e80c9;
	}
	#container div.search div.goto {
		padding: 5px;
		margin-top: 5px;
		margin-left: 10px;
		margin-bottom: 0px;
		background-color: #efefef;
		width: 97%;
		
	}
	#container div.search div.resultset {
		margin-top: 0px;
		margin-bottom: 15px;
		padding-top: 10px;
		padding-left: 10px;
		width: 98%;
	}
	#container div.search div:target {
		border: 2px solid red; 
	}
	#container div.search div.goto a {
		padding-left: 10px;
		padding-right: 10px;
	}
</style>