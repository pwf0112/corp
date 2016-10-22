<?php
namespace Corp\Manage;

use Corp\Uri;
use Corp\Base;
use Corp\Manager;

class Department
{
	public static function create($name, $parentId, $setting = [])
	{
		$url = sprintf(Uri::DEP_CREATE, Manager::$token);

		$json['name']     = $name;
		$json['parentid'] = $parentId;

		Manager::setMayValue($setting, ['order', 'id'], $json);

		return Base::postHttpsApi($url, $json);
	}

	public static function update($id, $date)
	{
		$url = sprintf(Uri::DEP_UPDATE, Manager::$token);

		$json['id'] = $id;

		Manager::setMayValue($date, ['name', 'parentid', 'order'], $json);

		return Base::postHttpsApi($url, $json);
	}

	public static function delete($id)
	{
		return Base::getHttpsApi(Uri::DEP_DELETE, [Manager::$token, $id]);
	}

	public static function getList($id = '')
	{
		return Base::getHttpsApi(Uri::DEP_LIST, [Manager::$token, $id]);
	}
}