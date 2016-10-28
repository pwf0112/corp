<?php
namespace Corp;

class Base
{
	use Traits;

	/**
	 * @var string 应用号ID
	 */
	public static $corpId;
	/**
	 * @var string 应用号权限组ACCESS_TOKEN
	 */
	public static $token;
	/**
	 * @var string 应用号客服ACCESS_TOKEN
	 */
	public static $kfToken;

	/**
	 * 获取应用号权限组的ACCESS_TOKEN
	 * @param string $secret 权限组的secret
	 * @return \stdClass 包含 token 和 expire 属性
	 * @throws Exception
	 */
	public static function token($secret)
	{
		$res = self::httpsGet(Uri::ACCESS_TOKEN, [self::$corpId, $secret]);

		$rep = new \stdClass();
		$rep->token  = $res->access_token;
		$rep->expire = $res->expires_in;

		return $rep;
	}

	/**
	 * 生成企业号授权URL
	 * @param string $redirect 原始URL地址
	 * @param string $state URL状态字符串
	 * @return string
	 */
	public static function oauthUrl($redirect, $state = '')
	{
		return vsprintf(Uri::OAUTH, [self::$corpId, $redirect, $state]);
	}
}