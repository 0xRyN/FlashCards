<?php


class Redirect //Classe utilitaire pour rediriger rapidement
{
	public static function to($link) {
		$temp = "Location: " . $link;
		header($temp);
	}
}