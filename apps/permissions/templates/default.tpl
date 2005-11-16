<div class="stage_contenu">

	<div class="stage_element">	
		<div class="stage_element_title">
			Gestion des permissions sur les applis
		</div>
		<div class="stage_element_data">
			{section name="i" loop="$applis"}
			<br /><a href="{kurl appli="$applis[i]"}">{$applis[i]}</a>
			{/section}
		</div>
	</div>		
</div>	
