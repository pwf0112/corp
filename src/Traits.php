<?php
namespace Corp;

trait Traits
{
	private static $token;

	public static function init($accessToken)
	{
		self::$token = $accessToken;
	}

	private static function setMayValue($date, $list, &$json)
	{
		foreach ($list as $key) {
			if (isset($date[$key])) $json[$key] = $date[$key];
		}
	}
}