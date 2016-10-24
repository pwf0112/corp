<?php
namespace Corp\Manage;

use Corp\Config;
use Corp\Traits;
use Corp\Uri;
use CurlX\Https;

class Media
{
	use Traits;

	public static function uploadImage($realPath)
	{
		return self::upload($realPath, 'image');
	}

	public static function uploadVoice($realPath)
	{
		return self::upload($realPath, 'voice');
	}

	public static function uploadVideo($realPath)
	{
		return self::upload($realPath, 'video');
	}

	public static function uploadFile($realPath)
	{
		return self::upload($realPath, 'file');
	}

	public static function get($mediaId)
	{
		$url = sprintf(Uri::MEDIA_GET, Config::$token, $mediaId);

		$res = Https::get($url);

		self::throwException($res);

		return $res;
	}

	private static function upload($realPath, $type)
	{
		$url = sprintf(Uri::MEDIA_UPLOAD, Config::$token, $type);

		$res = json_decode(Https::file($url, $realPath));

		self::throwException($res);

		$rep = new \stdClass;
		$rep->type = $res->type;
		$rep->id   = $res->media_id;
		$rep->at   = $res->created_at;

		return $rep;
	}
}