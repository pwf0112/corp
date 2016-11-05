<?php
namespace Corp;

abstract class Manager
{
	/**
	 * @var string 企业号权限组access_token值
	 */
	protected $token;

	public function __construct()
	{
		$this->token = Base::$token;
	}

	/**
	 * 设置企业号权限组access_token值
	 * @param string $token 权限组access_token值
	 * @return $this
	 */
	public function setToken($token)
	{
		$this->token = $token;
		return $this;
	}
}