<?php
namespace Corp\Manage;

use Corp\Exception;
use Corp\Manager;
use Corp\Uri;
use Corp\Traits;

/**
 * 企业号通讯录用户管理类
 */
class User extends Manager
{
	use Traits;

    /**
     * 创建企业号通讯录用户
     * @param string $userId 用户ID 企业号中唯一
     * @param string $name 用户名称
     * @param int|array $department 用户所属部门的ID(ID列表)
     * @param array $extData 非必须用户信息 必须包含 mobile|weixinid|email 其中至少一项 <br>
     *  用户信息=[position|mobile|gender|email|weixinid|avatar_mediaid(成员头像的mediaid)|extattr]
     *  其中 extattr=[[name(扩展字段名)|value(扩展字段值)],...]
     * @return bool
     * @throws Exception
     */
    public function create($userId, $name, $department, $extData)
    {
        $uri = sprintf(Uri::USER_CREATE, $this->token);

        $json = [
            'userid' => $userId,
            'name' => $name,
            'department' => is_int($department) ? [$department] : $department
        ];

        self::setMayValue($extData, [
            'position', 'mobile', 'gender', 'email', 'weixinid', 'avatar_mediaid'
        ], $json);

        if (isset($extData['extattr'])) {
            $json['extattr']['attrs'] = $extData['extattr'];
        }

        return self::httpsPost($uri, $json)['errcode'] === 0;
    }

    /**
     * 更新用户信息
     * @param string $userId 用户ID
     * @param array $extData 用户信息 必须包含 mobile|weixinid|email 其中至少一项 <br>
     *  用户信息=[name|position|mobile|gender|email|weixinid|enable|avatar_mediaid(成员头像的mediaid)|extattr] <br>
     *  其中 extattr=[[name(扩展字段名)|value(扩展字段值)],...]
     * @return bool
     * @throws Exception
     */
    public function update($userId, $extData)
	{
		$uri = sprintf(Uri::USER_UPDATE, $this->token);

		$json['userid'] = $userId;

		self::setMayValue($extData, [
			'name', 'position', 'mobile', 'gender', 'email', 'weixinid', 'enable', 'avatar_mediaid'
		], $json);

		if (isset($extData['extattr'])) {
			$json['extattr']['attrs'] = $extData['extattr'];
		}

		if (isset($extData['department'])) {
			$department = $extData['department'];
			$json['department'] = is_int($department) ? [$department] : $department;
		}

		return self::httpsPost($uri, $json)['errcode'] === 0;
    }

    /**
     * 删除用户
     * @param string $userId 用户ID
     * @return bool
     */
    public function delete($userId)
	{
		return self::httpsGet(Uri::USER_DELETE, [$this->token, $userId])['errcode'] === 0;
    }

    /**
     * 批量删除用户
     * @param array $userIds 用户ID列表
     * @return bool
     * @throws Exception
     */
    public function batchDelete(array $userIds)
	{
		$uri = sprintf(Uri::USER_BATCH_DELETE, $this->token);

		$json['useridlist'] = $userIds;

		return self::httpsPost($uri, $json)['errcode'] === 0;
    }

    /**
     * 获取用户信息
     * @param string $userId 用户ID
     * @return \stdClass
     *  userid|name|department(数组)|position|mobile|gender|email|weixinid|avatar(头像URL)|status|extattr
     * @throws Exception
     */
    public function get($userId)
	{
		return self::httpsGet(Uri::USER_GET, [$this->token, $userId]);
    }

    /**
     * 获取部门成员
     * @param int $depId 部门ID
     * @param int $state 成员状态条件
     * @param bool $fetch 是否递归获取
     * @return array 成员列表 成员(\stdClass类型)
     * @throws Exception
     */
    public function getSimpleList($depId, $state = 0, $fetch = true)
	{
		return self::httpsGet(
		    Uri::USER_SIMPLE_LIST, [$this->token, $depId, boolval($fetch), $state]
        )['userlist'];
    }

    /**
     * 获取部门成员详情
     * @param int $depId 部门ID
     * @param int $state 成员状态条件
     * @param bool $fetch 是否递归获取
     * @return array 成员列表 成员(\stdClass类型)
     * @throws Exception
     */
    public function getList($depId, $state = 0, $fetch = true)
	{
		return self::httpsGet(
		    Uri::USER_LIST, [$this->token, $depId, boolval($fetch), $state]
        )['userlist'];
	}

    /**
     * 根据code获取成员信息
     * @param string $code 微信授权CODE
     * @return array
     * @throws Exception
     */
    public function info($code)
	{
		return self::httpsGet(Uri::USER_INFO, [$this->token, $code]);
	}
}