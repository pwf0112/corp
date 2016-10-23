<?php
namespace Corp\Manage;

use Corp\Manager;
use Corp\Uri;
use CurlX\Https;

class Media
{
	public static function upload($realPath, $type)
	{
		$url = sprintf(Uri::MEDIA_UPLOAD, Manager::$token, $type);

		return json_decode(Https::file($url, $realPath));
	}

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
		$url = sprintf(Uri::MEDIA_GET, Manager::$token, $mediaId);

		return Https::get($url);
	}
}