<?php
namespace Corp;

class Manager
{
	public static $token;

	public static function setMayValue($date, $list, &$json)
	{
		foreach ($list as $key) {
			if (isset($date[$key])) $json[$key] = $date[$key];
		}
	}
}