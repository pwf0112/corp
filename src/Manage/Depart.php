<?php
namespace Corp\Manage;

use Corp\Config;
use Corp\Uri;
use Corp\Traits;

class Depart
{
	use Traits;

	public static function create($name, $parentId, $setting = [])
	{
		$url = sprintf(Uri::DEP_CREATE, Config::$token);

		$json['name']     = $name;
		$json['parentid'] = $parentId;

		self::setMayValue($setting, ['order', 'id'], $json);

		$res = self::httpsPost($url, $json);

		return intval($res->id);
	}

	public static function update($id, $date)
	{
		$url = sprintf(Uri::DEP_UPDATE, Config::$token);

		$json['id'] = $id;

		self::setMayValue($date, ['name', 'parentid', 'order'], $json);

		return self::httpsPost($url, $json);
	}

	public static function delete($id)
	{
		return self::httpsGet(Uri::DEP_DELETE, [Config::$token, $id]);
	}

	public static function getList($id = '')
	{
		return self::httpsGet(Uri::DEP_LIST, [Config::$token, $id])->department;
	}
}