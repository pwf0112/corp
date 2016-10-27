<?php
namespace Corp\Manage;

use Corp\Manager;
use Corp\Uri;
use Corp\Traits;

class Depart extends Manager
{
	use Traits;

	public function create($name, $parentId, $setting = [])
	{
		$url = sprintf(Uri::DEP_CREATE, $this->token);

		$json['name']     = $name;
		$json['parentid'] = $parentId;

		self::setMayValue($setting, ['order', 'id'], $json);

		$res = self::httpsPost($url, $json);

		return intval($res->id);
	}

	public function update($id, $date)
	{
		$url = sprintf(Uri::DEP_UPDATE, $this->token);

		$json['id'] = $id;

		self::setMayValue($date, ['name', 'parentid', 'order'], $json);

		return self::httpsPost($url, $json);
	}

	public function delete($id)
	{
		return self::httpsGet(Uri::DEP_DELETE, [$this->token, $id]);
	}

	public function getList($id = '')
	{
		return self::httpsGet(Uri::DEP_LIST, [$this->token, $id])->department;
	}
}