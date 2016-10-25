<?php
namespace Corp\Manage;

use Corp\Config;
use Corp\Uri;
use Corp\Traits;

class User
{
	use Traits;
	
    public static function create($userId, $name, $department, $extData)
    {
        $uri = sprintf(Uri::USER_CREATE, Config::$token);

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

        return self::httpsPost($uri, $json);
    }

	public static function update($userId, $extData)
	{
		$uri = sprintf(Uri::USER_UPDATE, Config::$token);

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

		return self::httpsPost($uri, $json);
    }

	public static function delete($userId)
	{
		return self::httpsGet(Uri::USER_DELETE, [Config::$token, $userId]);
    }

	public static function batchDelete(array $userIds)
	{
		$uri = sprintf(Uri::USER_BATCH_DELETE, Config::$token);

		$json['useridlist'] = $userIds;

		return self::httpsPost($uri, $json);
    }

	public static function get($userId)
	{
		return self::httpsGet(Uri::USER_GET, [Config::$token, $userId]);
    }

	public static function simpleList($depId, $state = 0, $fetch = 1)
	{
		return self::httpsGet(Uri::USER_SIMPLE_LIST, [Config::$token, $depId, $fetch, $state])->userlist;
    }

    public static function getList($depId, $state = 0, $fetch = 1)
	{
		return self::httpsGet(Uri::USER_LIST, [Config::$token, $depId, $fetch, $state])->userlist;
	}

	public static function info($code)
	{
		return self::httpsGet(Uri::USER_INFO, [Config::$token, $code]);
	}
}