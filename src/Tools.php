<?php
namespace Corp;

use CurlX\Https;

class Tools
{
	public static function getHttpsApi($apiName, $param)
	{
		$url = vsprintf($apiName, $param);

		$curl = Https::get($url);

		return json_decode($curl->response);
	}

	public static function postHttpsApi($apiUri, $json)
	{
		return json_decode(Https::post($apiUri, json_encode_cn($json))->response);
	}

    public static function getOauthUri($corpId, $redirect, $state = '')
    {
        return vsprintf(Uri::OAUTH, [$corpId, $redirect, $state]);
	}
}