<?php


class Hash //Génère des chaines de charactères cryptées
{
	public static function make($hash, $salt = '') {
		return hash('sha256', $hash . $salt);
	}

	public static function salt($length) {
		return bin2hex(random_bytes($length));
	}

	public static function unique() {
		return self::make(uniqid());
	}
}