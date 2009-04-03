<?php


class FormTestPost extends FormModel
{
	public function build()
	{
		//Validation du formulaire (en session) en fonction des valeurs postées (POST)
		if ($myform = KFormFactory::getForm('myform'))
		{
			if ($myform->validate($_POST))
			{
				//Continuation du traitement
			}
			else
			{
				//Arrêt du traitement et retour à la page de formulaire
			}
		}
	}
}

?>