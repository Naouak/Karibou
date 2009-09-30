<?php

class ScoreboardConfig extends AppConfigModel
{	
	public function formFields() {
	return array(
			"from" => array(
				"type" => "enum",
				"label" => _("À partir"),
				"values" => array(
					"top" => _("du haut"),
					"bottom" => _("du bas")
				),
				"radio" => false,
				"value" => "top",
				"required" => true
			),
			"number" => array(
				"type" => "float",
				"min" => 3,
				"max" => 100,
				"label" => _("Nombre de lignes à afficher"),
				"value" => 10
			),
			"hide" => array(
				"type" => "bool",
				"label" => _("Cacher mon score"),
				"value" => false
			)
		);
	}
}
