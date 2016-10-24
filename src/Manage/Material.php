<?php
namespace Corp\Manage;

use Corp\Config;
use Corp\Uri;
use CurlX\Https;
use Corp\Traits;

class Material
{
	use Traits;

	public static function addMpNews($news)
	{
		$item = [];

		self::makeNews($news, $item);

		$json['mpnews']['articles'] = $item;
		$url = sprintf(Uri::MATERIAL_ADD_MPNEWS, Config::$token);

		$res = self::httpsPost($url, $json);

		self::throwException($res);

		return $res->media_id;
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

    public static function get($mediaId)
    {
        $url = sprintf(Uri::MATERIAL_GET, Config::$token, $mediaId);

        $res = Https::get($url);

        if ($res->response_headers[2] == 'Content-Type: application/json; charset=utf-8') {
            $data = json_decode($res->response, true);
            if (isset($data->errcode)) {
                throw new \Corp\Exception($data->errmsg, $data->errcode);
            } else {
                return $data['mpnews']['articles'];
            }
        } else {
            return $res;
        }
	}

    public static function del($mediaId)
    {
        $res = self::httpsGet(Uri::MATERIAL_DEL, [Config::$token, $mediaId]);

        self::throwException($res);

        return true;
	}

    public static function updateMpNew($mediaId, $news)
    {
        $url = sprintf(Uri::MATERIAL_UPDATE_MPNEWS, Config::$token);

        $items = [];

		self::makeNews($news, $items);

        $json['media_id'] = $mediaId;
        $json['mpnews']['articles'] = $items;

        $res = self::httpsPost($url, $json);

        self::throwException($res);

        return true;
	}

    public static function getCount()
    {
        $res = self::httpsGet(Uri::MATERIAL_GET_COUNT, Config::$token);

        self::throwException($res);

        $count = new \stdClass();
        $count->total  = isset($res->total_count) ? $res->total_count : 0;
        $count->image  = isset($res->image_count) ? $res->image_count : 0;
        $count->voice  = isset($res->voice_count) ? $res->voice_count : 0;
        $count->video  = isset($res->video_count) ? $res->video_count : 0;
        $count->file   = isset($res->file_count) ? $res->file_count : 0;
        $count->mpnews = isset($res->mpnews_count) ? $res->mpnews_count : 0;

        return $count;
	}

    public static function batchMpNews($count = 50, $offset = 0)
    {
        return self::batchType('mpnews', $count, $offset);
	}

    public static function batchImage($count = 50, $offset = 0)
    {
        return self::batchType('image', $count, $offset);
    }

    public static function batchVoice($count = 50, $offset = 0)
    {
        return self::batchType('voice', $count, $offset);
    }

    public static function batchVideo($count = 50, $offset = 0)
    {
        return self::batchType('video', $count, $offset);
    }

    public static function batchFile($count = 50, $offset = 0)
    {
        $res = self::batchType('file', $count, $offset);

		self::throwException($res);


    }

    public static function uploadImg($realPath)
    {
        $res = json_decode(Https::file(sprintf(Uri::MEDIA_UPLOAD_IMG, Config::$token), $realPath));

        self::throwException($res);

        return $res->url;
    }

	private static function addType($realPath, $type)
	{
		$url = sprintf(Uri::MATERIAL_ADD, $type, Config::$token);

		$res = json_decode(Https::file($url, $realPath));

		self::throwException($res);

		return $res->media_id;
	}

    private static function batchType($type, $count, $offset)
    {
        $json['type'] = $type;
        $json['offset'] = $offset;
        $json['count'] = $count;

        $url = sprintf(Uri::MATERIAL_BATCH_GET, Config::$token);

        $res = self::httpsPost($url, $json);

        self::throwException($res);

		$rep = new \stdClass;
		$rep->type = $res->type;
		$rep->list = $res->itemlist;

        return $rep;
	}
}