<?php
class Mc2Config extends AppConfigModel {
	public function formFields() {
		return array(
			"num_lines" => array(
				"type" => "int",
				"value" => 5,
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
			"button" => array(
				"type" => "bool",
				"value" => false,
				"label" => _('Show "Send" button')
			)
		);
	}
}
