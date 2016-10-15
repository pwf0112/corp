<?php
namespace Corp;

class Base
{
	public static function getHttpsApi($apiName, $param)
	{
		$url = vsprintf($apiName, $param);

		$curl = \CurlX\Https::get($url);

		return json_decode($curl->response);
	}

    public static function getOauthUri($corpId, $redirect, $state = '')
    {
        return vsprintf(Api::OAUTH, [$corpId, $redirect, $state]);
	}
}