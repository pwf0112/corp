<?php
namespace Corp\Manage;

use Corp\Manager;
use Corp\Uri;
use Corp\Traits;

class User extends Manager 
{
	use Traits;
	
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

        return self::httpsPost($uri, $json);
    }

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

		return self::httpsPost($uri, $json);
    }

	public function delete($userId)
	{
		return self::httpsGet(Uri::USER_DELETE, [$this->token, $userId]);
    }

	public function batchDelete(array $userIds)
	{
		$uri = sprintf(Uri::USER_BATCH_DELETE, $this->token);

		$json['useridlist'] = $userIds;

		return self::httpsPost($uri, $json);
    }

	public function get($userId)
	{
		return self::httpsGet(Uri::USER_GET, [$this->token, $userId]);
    }

	public function getSimpleList($depId, $state = 0, $fetch = 1)
	{
		return self::httpsGet(Uri::USER_SIMPLE_LIST, [$this->token, $depId, $fetch, $state])->userlist;
    }

    public function getList($depId, $state = 0, $fetch = 1)
	{
		return self::httpsGet(Uri::USER_LIST, [$this->token, $depId, $fetch, $state])->userlist;
	}

	public function info($code)
	{
		return self::httpsGet(Uri::USER_INFO, [$this->token, $code]);
	}
}