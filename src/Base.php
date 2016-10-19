<?php
namespace Corp;

class Base
{
	public static function token($corpId, $secret)
	{
		return Tools::getHttpsApi(Uri::ACCESS_TOKEN, [$corpId, $secret]);
	}

    public static function userInfo($token, $code)
    {
        return Tools::getHttpsApi(Uri::USER_INFO, [$token, $code]);
	}
}