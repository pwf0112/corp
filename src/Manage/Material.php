<?php
namespace Corp\Manage;


use Corp\Base;
use Corp\Manager;
use Corp\Uri;
use CurlX\Https;

class Material
{
	public static function addMpNews($news)
	{
		$item = [];
		foreach ($news as $new) {
			$temp['title'] = $new['title'];
			$temp['thumb_media_id'] = $new['thumb'];
			$temp['content'] = $new['content'];
			if (isset($new['author'])) {
				$temp['author'] = $new['author'];
			}
			if (isset($new['source'])) {
				$temp['content_source_url'] = $new['source'];
			}
			if (isset($new['digest'])) {
				$temp['digest'] = $new['digest'];
			}
			if (isset($new['cover'])) {
				$temp['show_cover_pic'] = strval($new['cover']);
			}

			$item[] = $temp;
		}

		$json['mpnews']['articles'] = $item;
		$url = sprintf(Uri::MATERIAL_ADD_MPNEWS, Manager::$token);

		return Base::postHttpsApi($url, $json);
	}

	public static function addImage($realPath)
	{
		return self::addType($realPath, 'image');
	}

	public static function addVoice($realPath)
	{
		return self::addType($realPath, 'voice');
	}

	public static function addVideo($realPath)
	{
		return self::addType($realPath, 'video');
	}

	public static function addFile($realPath)
	{
		return self::addType($realPath, 'file');
	}

	private static function addType($realPath, $type)
	{
		$url = sprintf(Uri::MATERIAL_ADD, $type, Manager::$token);

		return json_decode(Https::file($url, $realPath));
	}
}