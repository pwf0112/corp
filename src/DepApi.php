<?php
namespace Corp;

class DepApi
{
	private static $token;

	public static function init($accessToken)
	{
		self::$token = $accessToken;
	}

	public static function create($name, $parentId, $setting = [])
	{
		$url = sprintf(ApiUri::DEP_CREATE, self::$token);

		$json['name']     = $name;
		$json['parentid'] = $parentId;

		self::setMayValue($setting, ['order', 'id'], $json);

		return Tools::postHttpsApi($url, $json);
	}

	public static function update($id, $date)
	{
		$url = sprintf(ApiUri::DEP_UPDATE, self::$token);

		$json['id'] = $id;

		self::setMayValue($date, ['name', 'parentid', 'order'], $json);

		return Tools::postHttpsApi($url, $json);
	}

	public static function delete($id)
	{
		return Tools::getHttpsApi(ApiUri::DEP_DELETE, [self::$token, $id]);
	}

	public static function getList($id = '')
	{
		return Tools::getHttpsApi(ApiUri::DEP_LIST, [self::$token, $id]);
	}

	private static function setMayValue($date, $list, &$json)
	{
		foreach ($list as $key) {
			if (isset($date[$key])) $json[$key] = $date[$key];
		}
	}
}