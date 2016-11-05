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

    private $_corpId;

    public function __construct()
    {
        $this->_corpId = self::$corpId;
    }

    /**
     * 设置企业号ID
     * @param string $corpId 企业号ID
     * @return $this
     */
    public function setCorpId($corpId)
    {
        $this->_corpId = $corpId;
        return $this;
    }

	/**
	 * 获取应用号权限组的ACCESS_TOKEN
     * @param string $secret 权限组的secret
     * @return array
     * @throws Exception
	 */
	public function getToken($secret)
	{
		return self::httpsGet(Uri::ACCESS_TOKEN, [$this->_corpId, $secret]);
	}

	/**
	 * 生成企业号授权URL
     * @param string $redirect 原始URL地址
     * @param string $state URL状态字符串
     * @return string
	 */
	public function oauthUrl($redirect, $state = '')
	{
		return vsprintf(Uri::OAUTH, [$this->_corpId, $redirect, $state]);
	}
}