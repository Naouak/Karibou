<?
if ($this->vars['permission'] > _DEFAULT_)
{
	if (!isset($this->vars['newsgrand']))
	{
		include("newsmessage.tpl");
	}

	if (isset($theArticle))
	{
		$idNews = $theArticle->getID();
		echo '<div class="aNews">';
			echo '<div class="time">'.$theArticle->getDate().'</div>';
			echo '<h1>';
			if ($theArticle->getTitle()=="")
				echo _('NOTITLE');
			else
				echo $theArticle->getTitle();
			echo '</h1>';
			echo '<div class="author">';
				echo _('BY');
				echo userlink(array('user'=>$theArticle->getAuthorObject(), 'showpicture'=>true));
			echo '</div>';
		if ($theArticle->getAuthorId() == $this->vars['currentUserId'])
		{
			echo '<div class="controls">';
				echo '<form action="'.kurl(array('app'=>"news", 'page'=>"modify", 'id'=>"$idNews")).'" method="get">';
					echo '<input type="submit" value="'._('MODIFY').'" />';
				echo '</form>';
				echo '<form action="'.kurl(array('action'=>"post")).'" method="post">';
					echo '<input type="hidden" name="postType" value="delNews">';
					echo '<input type="hidden" name="id" value="{$idNews}">';
					echo '<input type="submit" value="'._('DELETE').'" onclick="return confirm(\''._('SURE_TO_DELETE_ARTICLE').'\');"/>';
				echo '</form>';
			echo '</div>';
		}
			echo '<div class="groupsdestination">'._('NEWS_GROUPS_DESTINATION').' '.$theArticle->getGroupName().'</div>';
			echo '<div class="content">'.$theArticle->getContentXHTML().'</div>';
			if ($this->vars['permission'] > _READ_ONLY_)
			{
			}
			echo '<div class="commentsHidden">';
				echo $theArticle->getNbComments().' '._('COMMENT').' '.(($theArticle->getNbComments()>1)?'s':''); 
				if ($theArticle->getNbComments()>0)
				{
					echo '(';
					if (isset($this->vars['displayComments']) && ($this->vars['displayComments'] == 0) || !isset($this->vars['displayComments']))
					{
						echo '<a href="'.kurl(array('page'=>"view", 'id'=>$theArticle->getID(), 'displayComments'=>"1")).'">'._('DISPLAY_COMMENTS').'</a>';
					}
					else
					{
						echo '<a href="'.kurl(array('page'=>"view", 'id'=>$theArticle->getID(), 'displayComments'=>"0")).'">'._('HIDE_COMMENTS').'</a>';
					}
					echo ')';
				}
				echo '<a href="'.kurl(array('page'=>"addcomment", 'id'=>$idNews)).'">'._('ADD_COMMENT').'</a>';
			echo '</div>';
			if (isset($addComment))
			{
				echo '<div class="newNewsCommentForm">';
					echo '<h3>'._('ADD_A_COMMENT_TO_ARTICLE').' "';
						if ($theArticle->getTitle()=="")
							echo _('NOTITLE');
						else
							echo $theArticle->getTitle();
					echo '"</h3>';
					echo '<form action="'.kurl(array('action'=>"post")).'" method="post">';
						echo '
							<input type="hidden" name="postType" value="newNewsComment">
							<input type="hidden" name="id_news" value="'.$theArticle->getId().'" />
							<input type="hidden" name="id_parent" value="" />
							<label for="title">'._('COMMENT_TITLE').' :</label> <input type="text" name="title" id="title" size="40" value="';
							if ($theArticle->getID()==$theNewsCurrentComment['newsId'])
								echo $theNewsCurrentComment['title'];
						echo '"/>
							<label for="description">'._('COMMENT_DESCRIPTION').' :</label><textarea name="content" id="description" rows="5" cols="80">{if ($theArticle->getId()==$theNewsCurrentComment.newsId)}{$theNewsCurrentComment.content}{/if}</textarea>
							<input type="submit" value="'._('POST_COMMENT_ON_ARTICLE').'" class="button" />
							';
					echo '</form>';
				echo '</div>';
			}
			elseif (isset($this->vars['displayComments']) && ($this->vars['displayComments'] == 0))
			{
			}
			else
			{
				echo '<div class="commentsShown">';
				//{section name=j loop=$theArticleComments step=1}
				if (isset($this->vars['theArticleComments']) && (count($this->vars['theArticleComments'])>0) && $this->vars['theArticleComments'] !== FALSE)
				foreach($this->vars['theArticleComments'] as $articleComment)
				{
					echo '<div class="aComment">';
						echo '<div class="author">';
							echo _('BY');
							echo userlink(array('user'=>$articleComment->getAuthorObject(), 'showpicture'=>true));
						echo '</div>';
						echo '<div class="time">'.$articleComment->getDate().'</div>';
						echo '<div class="title">'.$articleComment->getTitle().'</div>';
						echo '<div class="content">'.$articleComment->getContentXHTML().'</div>';
					echo '</div>';
				}
				//{/section}
				echo '</div>';
			}
		echo '</div>';
	}
	else
	{
			echo '<div class="error">';
		if (isset($this->vars['notingroup']) && $this->vars['notingroup'] == TRUE)
				echo _('NOT_AUTHORIZED_TO_VIEW');
		else
				echo _('ARTICLE_NOT_FOUND');
			echo '</div>';
	}
}
else
{
	####
}