<?php
namespace Corp;

class Dep
{
	use Traits;

	public static function create($name, $parentId, $setting = [])
	{
		$url = sprintf(Uri::DEP_CREATE, self::$token);

		$json['name']     = $name;
		$json['parentid'] = $parentId;

		self::setMayValue($setting, ['order', 'id'], $json);

		return Tools::postHttpsApi($url, $json);
	}

	public static function update($id, $date)
	{
		$url = sprintf(Uri::DEP_UPDATE, self::$token);

		$json['id'] = $id;

		self::setMayValue($date, ['name', 'parentid', 'order'], $json);

		return Tools::postHttpsApi($url, $json);
	}

	public static function delete($id)
	{
		return Tools::getHttpsApi(Uri::DEP_DELETE, [self::$token, $id]);
	}

	public static function getList($id = '')
	{
		return Tools::getHttpsApi(Uri::DEP_LIST, [self::$token, $id]);
	}
}