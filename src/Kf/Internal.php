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
        $data = self::initReceiveData($kf, $senderUserId);

        $data->vjson['msgtype'] = 'text';
        $data->json['text']['content'] = $content;

        return self::requestReceive($data->url, $data->json);
    }

    public static function receiveImage($kf, $senderUserId, $mediaId)
    {
        $data = self::initReceiveData($kf, $senderUserId);

        $data->json['msgtype'] = 'image';
        $data->json['image']['media_id'] = $mediaId;

        return self::requestReceive($data->url, $data->json);
    }

    public static function receiveFile($kf, $senderUserId, $mediaId)
    {
        $data = self::initReceiveData($kf, $senderUserId);

        $data->json['msgtype'] = 'file';
        $data->json['file']['media_id'] = $mediaId;

        return self::requestReceive($data->url, $data->json);
    }

    public static function receiveVoice($kf, $senderUserId, $mediaId)
    {
        $data = self::initReceiveData($kf, $senderUserId);

        $data->json['msgtype'] = 'voice';
        $data->json['voice']['media_id'] = $mediaId;

        return self::requestReceive($data->url, $data->json);
    }

    private static function initReceiveData($kf, $senderUserId)
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
		return self::httpsPost($url, $json);
    }
}