<?php
/* Made in Rémy Sanchez <remy.sanchez@hyperthese.net> */ 
 
/**
 * Class klockConfig
 *
 * @package applications
 */

class klockConfig extends AppConfigModel
{	
	public function formFields() {
        return array(
            "mode" => array(
				"type" => "enum",
				"label" => _("Mode"),
				"values" => array(
					"binary" => _("Binary"),
					"analog" => _("Analogical"),
					"digital" => _("Digital")
				),
				"radio" => false,
				"value" => "digital",
				"required" => true
			),
			"imprecision" => array(
				"type" => "float",
				"min" => 5,
				"label" => _("Imprecision"),
				"value" => 5
			)
		);
	}
}
?>
