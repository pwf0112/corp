<?php
namespace Corp;

use CurlX\Https;

class Base
{
	public static function token($corpId, $secret)
	{
		return self::getHttpsApi(Uri::ACCESS_TOKEN, [$corpId, $secret]);
	}

    public static function userInfo($token, $code)
    {
        return self::getHttpsApi(Uri::USER_INFO, [$token, $code]);
	}

	public static function getHttpsApi($apiName, $param)
	{
		$url = vsprintf($apiName, $param);

		$curl = Https::get($url);

		return json_decode($curl->response);
	}

	public static function postHttpsApi($apiUri, $json)
	{
		return json_decode(Https::post($apiUri, json_encode($json, JSON_UNESCAPED_UNICODE))->response);
	}

	public static function getOauthUri($corpId, $redirect, $state = '')
	{
		return vsprintf(Uri::OAUTH, [$corpId, $redirect, $state]);
	}
}