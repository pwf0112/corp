<?php
namespace Corp\Manage;

use Corp\Uri;
use Corp\Base;
use Corp\Manager;

class User
{
    public static function create($userId, $name, $department, $extData)
    {
        $uri = sprintf(Uri::USER_CREATE, Manager::$token);

        $json = [
            'userid' => $userId,
            'name' => $name,
            'department' => is_int($department) ? [$department] : $department
        ];

        Manager::setMayValue($extData, [
            'position', 'mobile', 'gender', 'email', 'weixinid', 'avatar_mediaid'
        ], $json);

        if (isset($extData['extattr'])) {
            $json['extattr']['attrs'] = $extData['extattr'];
        }

        return Base::postHttpsApi($uri, $json);
    }

	public static function update($userId, $extData)
	{
		$uri = sprintf(Uri::USER_UPDATE, Manager::$token);

		$json['userid'] = $userId;

		Manager::setMayValue($extData, [
			'name', 'position', 'mobile', 'gender', 'email', 'weixinid', 'enable', 'avatar_mediaid'
		], $json);

		if (isset($extData['extattr'])) {
			$json['extattr']['attrs'] = $extData['extattr'];
		}

		if (isset($extData['department'])) {
			$department = $extData['department'];
			$json['department'] = is_int($department) ? [$department] : $department;
		}

		return Base::postHttpsApi($uri, $json);
    }

	public static function delete($userId)
	{
		return Base::getHttpsApi(Uri::USER_DELETE, [Manager::$token, $userId]);
    }

	public static function batchDelete(array $userIds)
	{
		$uri = sprintf(Uri::USER_BATCH_DELETE, Manager::$token);

		$json['useridlist'] = $userIds;

		return Base::postHttpsApi($uri, $json);
    }

	public static function get($userId)
	{
		return Base::getHttpsApi(Uri::USER_GET, [Manager::$token, $userId]);
    }

	public static function simpleList($depId, $state = 0, $fetch = 1)
	{
		return Base::getHttpsApi(Uri::USER_SIMPLE_LIST, [Manager::$token, $depId, $fetch, $state]);
    }

    public static function getList($depId, $state = 0, $fetch = 1)
	{
		return Base::getHttpsApi(Uri::USER_LIST, [Manager::$token, $depId, $fetch, $state]);
	}
}