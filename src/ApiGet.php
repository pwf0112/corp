<?php
namespace Corp;


class ApiGet
{
	public static function token($corpId, $secret)
	{
		return Base::getHttpsApi(Api::ACCESS_TOKEN, [$corpId, $secret]);
	}

    public static function userInfo($token, $code)
    {
        return Base::getHttpsApi(Api::USER_INFO, [$token, $code]);
	}
}