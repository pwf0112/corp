<?php
namespace Corp\Manage;


use Corp\Base;
use Corp\Manager;
use Corp\Uri;

class Material
{
	public static function addMpNews()
	{
		$news = func_get_args();
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
}