<?php
namespace Corp\Kf;

use Corp\Config;
use Corp\Traits;
use Corp\Uri;

class Internal
{
	use Traits;

    public static function receiveText($kf, $senderUserId, $content)
    {
        $data = self::base($kf, $senderUserId);

        $json = $data->json;
        $json['msgtype'] = 'text';
        $json['text']['content'] = $content;

        return self::requestReceive($data->url, $json);
    }

    public static function receiveImage($kf, $senderUserId, $mediaId)
    {
        $data = self::base($kf, $senderUserId);

        $json = $data->json;
        $json['msgtype'] = 'image';
        $json['image']['media_id'] = $mediaId;

        return self::requestReceive($data->url, $json);
    }

    public static function receiveFile($kf, $senderUserId, $mediaId)
    {
        $data = self::base($kf, $senderUserId);

        $json = $data->json;
        $json['msgtype'] = 'file';
        $json['file']['media_id'] = $mediaId;

        return self::requestReceive($data->url, $json);
    }

    public static function receiveVoice($kf, $senderUserId, $mediaId)
    {
        $data = self::base($kf, $senderUserId);

        $json = $data->json;
        $json['msgtype'] = 'voice';
        $json['voice']['media_id'] = $mediaId;

        return self::requestReceive($data->url, $json);
    }

    private static function base($kf, $senderUserId)
    {
        $url = sprintf(Uri::KF_SEND, Config::$kfToken);

        $json['sender'] = [
            'type' => 'userid',
            'id' => $senderUserId
        ];

        $json['receiver'] = [
            'type' => 'kf',
            'id' => $kf
        ];

        $res = new \stdClass;
        $res->url = $url;
        $res->json = $json;

        return $res;
    }

	private static function requestReceive($url, $json)
	{
		$res = self::httpsPost($url, $json);

		self::throwException($res);

		return true;
    }
}