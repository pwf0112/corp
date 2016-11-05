<?php
namespace Corp;

use CurlX\Https;

trait Traits
{
	private static function setMayValue(array $data,array $list, &$json)
	{
		foreach ($list as $key) {
			if (isset($data[$key])) $json[$key] = $data[$key];
		}
	}

	private static function makeNews($news, &$items)
	{
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
			if (isset($new['show'])) {
				$temp['show_cover_pic'] = strval($new['show']);
			}

			$items[] = $temp;
		}
	}

	private static function httpsGet($apiName, $param)
	{
		$url  = vsprintf($apiName, $param);
		$curl = Https::get($url);
		$res  = json_decode($curl->response, true);
		self::throwException($res);
		return $res;
	}

	private static function httpsPost($apiUri, $json)
	{
		$res = json_decode(
		    Https::post($apiUri, json_encode($json, JSON_UNESCAPED_UNICODE))->response,
            true
        );
		self::throwException($res);
		return $res;
	}

	private static function throwException($res)
	{
		if (isset($res->errcode) && $res->errcode !== 0) {
			throw new \Corp\Exception($res->errmsg, $res->errcode);
		}
	}
}