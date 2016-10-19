<?php
namespace Corp;

class User
{
    use Traits;

    public static function create($userId, $name, $department, $extData)
    {
        $uri = sprintf(Uri::USER_CREATE, self::$token);

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

        return Tools::postHttpsApi($uri, $json);
    }

	public static function update($userId, $extData)
	{
		$uri = sprintf(Uri::USER_UPDATE, self::$token);

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

		return Tools::postHttpsApi($uri, $json);
    }

	public static function delete($userId)
	{
		return Tools::getHttpsApi(Uri::USER_DELETE, [self::$token, $userId]);
    }

	public static function batchDelete(array $userIds)
	{
		$uri = sprintf(Uri::USER_BATCH_DELETE, self::$token);

		$json['useridlist'] = $userIds;

		return Tools::postHttpsApi($uri, $json);
    }

	public static function get($userId)
	{
		return Tools::getHttpsApi(Uri::USER_GET, [self::$token, $userId]);
    }

	public static function simpleList($depId, $state = 0, $fetch = 1)
	{
		return Tools::getHttpsApi(Uri::USER_SIMPLE_LIST, [self::$token, $depId, $fetch, $state]);
    }

    public static function getList($depId, $state = 0, $fetch = 1)
	{
		return Tools::getHttpsApi(Uri::USER_LIST, [self::$token, $depId, $fetch, $state]);
	}
}