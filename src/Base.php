<?php
namespace Corp;

class Base
{
	use Traits;
	
	public static function token($secret)
	{
		$res = self::httpsGet(Uri::ACCESS_TOKEN, [Config::$corpId, $secret]);

		self::throwException($res);

		$rep = new \stdClass;
		$rep->token = $res->access_token;
		$rep->expire = $res-> expires_in;

		return $rep;
	}

	public static function oauthUrl($redirect, $state = '')
	{
		return vsprintf(Uri::OAUTH, [Config::$corpId, $redirect, $state]);
	}
}