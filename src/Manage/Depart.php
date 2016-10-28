<?php
namespace Corp\Manage;

use Corp\Exception;
use Corp\Manager;
use Corp\Uri;
use Corp\Traits;

/**
 * 企业号部门管理类
 */
class Depart extends Manager
{
	use Traits;

	/**
	 * 创建新部门
	 * @param string $name 部门名称
	 * @param int $parentId 父部门ID,根部门ID为1
	 * @param array $setting order(在父部门中的次序值)|id(部门ID)
	 * @return int 新创建部门的ID
	 * @throws Exception
	 */
	public function create($name, $parentId = 1,array $setting = [])
	{
		$url = sprintf(Uri::DEP_CREATE, $this->token);

		$json['name']     = $name;
		$json['parentid'] = $parentId;

		self::setMayValue($setting, ['order', 'id'], $json);

		$res = self::httpsPost($url, $json);

		return intval($res->id);
	}

	/**
	 * 更新部门信息
	 * @param int $id 更新部门ID
	 * @param array $update name(名称)|parentid(父部门ID)|order(在父部门中的次序值)
	 * @return bool
	 * @throws Exception
	 */
	public function update($id,array $update)
	{
		$url = sprintf(Uri::DEP_UPDATE, $this->token);

		$json['id'] = $id;

		self::setMayValue($update, ['name', 'parentid', 'order'], $json);

		return self::httpsPost($url, $json)->errcode === 0;
	}

	/**
	 * 删除部门
	 * @param int $id 删除部门ID
	 * @return bool
	 * @throws Exception
	 */
	public function delete($id)
	{
		return self::httpsGet(Uri::DEP_DELETE, [$this->token, $id])->errcode === 0;
	}

	/**
	 * 获取指定部门及其下的子部门
	 * @param int $id 部门ID
	 * @return array 部门列表 <br>
     *  部门=[id(部门id)|name(部门名称)|parentid(父亲部门id)|order(在父部门中的次序值)]
	 * @throws Exception
	 */
	public function getList($id = 1)
	{
		return self::httpsGet(Uri::DEP_LIST, [$this->token, $id])->department;
	}
}