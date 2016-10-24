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

    public static function get($mediaId)
    {
        $url = sprintf(Uri::MATERIAL_GET, Manager::$token, $mediaId);

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
        $res = Base::getHttpsApi(Uri::MATERIAL_DEL, [Manager::$token, $mediaId]);

        self::checkException($res);

        return true;
	}

    public static function updateMpNew($mediaId, $news)
    {
        $url = sprintf(Uri::MATERIAL_UPDATE_MPNEWS, Manager::$token);

        $items = [];
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

            $items[] = $temp;
        }

        $json['media_id'] = $mediaId;
        $json['mpnews']['articles'] = $items;

        $res = Base::postHttpsApi($url, $json);

        self::checkException($res);

        return true;
	}

    public static function getCount()
    {
        $res = Base::getHttpsApi(Uri::MATERIAL_GET_COUNT, Manager::$token);

        self::checkException($res);

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
        return self::batchType('file', $count, $offset);
    }

    public static function uploadImg($realPath)
    {
        $res = json_decode(Https::file(sprintf(Uri::MEDIA_UPLOAD_IMG, Manager::$token), $realPath));

        self::checkException($res);

        return $res->url;
    }

	private static function addType($realPath, $type)
	{
		$url = sprintf(Uri::MATERIAL_ADD, $type, Manager::$token);

		return json_decode(Https::file($url, $realPath));
	}

    private static function checkException($res)
    {
        if (isset($res->errcode) && $res->errcode !== 0) {
            throw new \Corp\Exception($res->errmsg, $res->errcode);
        }
	}

    private static function batchType($type, $count, $offset)
    {
        $json['type'] = $type;
        $json['offset'] = $offset;
        $json['count'] = $count;

        $url = sprintf(Uri::MATERIAL_BATCH_GET, Manager::$token);

        $res = Base::postHttpsApi($url, $json);

        self::checkException($res);

        return $res->itemlist;
	}
}