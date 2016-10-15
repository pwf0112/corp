<?php
namespace Corp;


class BaseApi
{
	public static function token($corpId, $secret)
	{
		return Tools::getHttpsApi(ApiUri::ACCESS_TOKEN, [$corpId, $secret]);
	}

    public static function userInfo($token, $code)
    {
        return Tools::getHttpsApi(ApiUri::USER_INFO, [$token, $code]);
	}
}