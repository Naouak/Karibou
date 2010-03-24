<?php
class Mc2Config extends AppConfigModel {
	public function formFields() {
		$e = new Emoticons(null);

		$themes = array();
		foreach($e->get_emoticon_themes() as $t) {
			$themes[$t] = $t;
		}

		return array(
			"num_lines" => array(
				"type" => "int",
				"value" => 15,
				"label" => _('Number of lines'),
				"min" => 1,
				"max" => 100
			),
			"invert" => array(
				"type" => "bool",
				"value" => false,
				"label" => _('Inverted messages order')
			),
			"show_msg" => array(
				"type" => "bool",
				"value" => true,
				"label" => _('Show messages'),
			),
			"show_score" => array(
				"type" => "bool",
				"value" => false,
				"label" => _('Show score notifications')
			),
			"richtext" => array(
				"type" => "bool",
				"value" => true,
				"label" => _('Show rich text')
			),
			"emoticon_theme" => array(
				"type" => "enum",
				"value" => "Default",
				"values" => $themes,
				"label" => _('Emoticon theme')
			),
			"button" => array(
				"type" => "bool",
				"value" => false,
				"label" => _('Show "Send" button')
			)
		);
	}
}
