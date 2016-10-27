<?php
namespace Corp\Manage;

use Corp\Manager;
use Corp\Traits;
use Corp\Uri;
use CurlX\Https;


/**
 * 企业号永久素材管理类
 */
class Material extends Manager
{
	use Traits;

	/**
	 * @param $news
	 * @return mixed
	 */
	public function addMpNews($news)
	{
		$item = [];

		self::makeNews($news, $item);

		$json['mpnews']['articles'] = $item;
		$url = sprintf(Uri::MATERIAL_ADD_MPNEWS, $this->token);

		return self::httpsPost($url, $json)->media_id;
	}

	/**
	 * @param $realPath
	 *
	 * @return mixed
	 */
	public function addImage($realPath)
	{
		return self::addType($realPath, 'image');
	}

	/**
	 * @param $realPath
	 *
	 * @return mixed
	 */
	public function addVoice($realPath)
	{
		return self::addType($realPath, 'voice');
	}

	/**
	 * @param $realPath
	 *
	 * @return mixed
	 */
	public function addVideo($realPath)
	{
		return self::addType($realPath, 'video');
	}

	/**
	 * @param $realPath
	 *
	 * @return mixed
	 */
	public function addFile($realPath)
	{
		return self::addType($realPath, 'file');
	}

	/**
	 * @param $mediaId
	 *
	 * @return \Curl\Curl
	 * @throws \Corp\Exception
	 */
	public function get($mediaId)
    {
        $url = sprintf(Uri::MATERIAL_GET, $this->token, $mediaId);

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

	/**
	 * @param $mediaId
	 *
	 * @return mixed
	 */
	public function delete($mediaId)
    {
        return self::httpsGet(Uri::MATERIAL_DEL, [$this->token, $mediaId]);
	}

	/**
	 * @param $mediaId
	 * @param $news
	 *
	 * @return mixed
	 */
	public function updateMpNew($mediaId, $news)
    {
        $url = sprintf(Uri::MATERIAL_UPDATE_MPNEWS, $this->token);

        $items = [];

		self::makeNews($news, $items);

        $json['media_id'] = $mediaId;
        $json['mpnews']['articles'] = $items;

        return self::httpsPost($url, $json);
	}

	/**
	 * @return \stdClass
	 */
	public function getCount()
    {
        $res = self::httpsGet(Uri::MATERIAL_GET_COUNT, $this->token);

        $count = new \stdClass();
        $count->total  = isset($res->total_count) ? $res->total_count : 0;
        $count->image  = isset($res->image_count) ? $res->image_count : 0;
        $count->voice  = isset($res->voice_count) ? $res->voice_count : 0;
        $count->video  = isset($res->video_count) ? $res->video_count : 0;
        $count->file   = isset($res->file_count) ? $res->file_count : 0;
        $count->mpnews = isset($res->mpnews_count) ? $res->mpnews_count : 0;

        return $count;
	}

	/**
	 * @param int $count
	 * @param int $offset
	 *
	 * @return \stdClass
	 */
	public function batchMpNews($count = 50, $offset = 0)
    {
        return self::batchType('mpnews', $count, $offset);
	}

	/**
	 * @param int $count
	 * @param int $offset
	 *
	 * @return \stdClass
	 */
	public function batchImage($count = 50, $offset = 0)
    {
        return self::batchType('image', $count, $offset);
    }

	/**
	 * @param int $count
	 * @param int $offset
	 *
	 * @return \stdClass
	 */
	public function batchVoice($count = 50, $offset = 0)
    {
        return self::batchType('voice', $count, $offset);
    }

	/**
	 * @param int $count
	 * @param int $offset
	 *
	 * @return \stdClass
	 */
	public function batchVideo($count = 50, $offset = 0)
    {
        return self::batchType('video', $count, $offset);
    }

	/**
	 * @param int $count
	 * @param int $offset
	 */
	public function batchFile($count = 50, $offset = 0)
    {
        $res = self::batchType('file', $count, $offset);

		self::throwException($res);


    }

	/**
	 * @param $realPath
	 *
	 * @return mixed
	 */
	public function uploadImg($realPath)
    {
        return json_decode(Https::file(sprintf(Uri::MEDIA_UPLOAD_IMG, $this->token), $realPath))->url;
    }

	/**
	 * @param $realPath
	 * @param $type
	 *
	 * @return mixed
	 */
	private function addType($realPath, $type)
	{
		$url = sprintf(Uri::MATERIAL_ADD, $type, $this->token);

		return json_decode(Https::file($url, $realPath))->media_id;
	}

	/**
	 * @param $type
	 * @param $count
	 * @param $offset
	 *
	 * @return \stdClass
	 */
	private function batchType($type, $count, $offset)
    {
        $json['type'] = $type;
        $json['offset'] = $offset;
        $json['count'] = $count;

        $url = sprintf(Uri::MATERIAL_BATCH_GET, $this->token);

        $res = self::httpsPost($url, $json);

		$rep = new \stdClass;
		$rep->type = $res->type;
		$rep->list = $res->itemlist;

        return $rep;
	}
}