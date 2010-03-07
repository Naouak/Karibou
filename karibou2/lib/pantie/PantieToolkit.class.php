<?php

class PantieToolkit {
	static public function intAsString($int) {
		return
			chr(($int >> 24) & 0xFF) .
			chr(($int >> 16) & 0xFF) .
			chr(($int >> 8) & 0xFF) .
			chr($int & 0xFF);
	}

	static public function stringAsInt($str) {
		return
			(ord($str[0]) << 24)
			+ (ord($str[1]) << 16)
			+ (ord($str[2]) << 8)
			+ (ord($str[3]));
	}
}
