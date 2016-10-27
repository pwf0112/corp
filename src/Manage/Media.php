<?php
namespace Corp\Manage;

use Corp\Manager;
use Corp\Traits;
use Corp\Uri;
use CurlX\Https;

class Media extends Manager 
{
	use Traits;

	public function uploadImage($realPath)
	{
		return self::upload($realPath, 'image');
	}

	public function uploadVoice($realPath)
	{
		return self::upload($realPath, 'voice');
	}

	public function uploadVideo($realPath)
	{
		return self::upload($realPath, 'video');
	}

	public function uploadFile($realPath)
	{
		return self::upload($realPath, 'file');
	}

	public function get($mediaId)
	{
		$url = sprintf(Uri::MEDIA_GET, $this->token, $mediaId);

		return Https::get($url);
	}

	private function upload($realPath, $type)
	{
		$url = sprintf(Uri::MEDIA_UPLOAD, $this->token, $type);

		$res = json_decode(Https::file($url, $realPath));

		self::throwException($res);

		$rep = new \stdClass;
		$rep->type = $res->type;
		$rep->id   = $res->media_id;
		$rep->at   = $res->created_at;

		return $rep;
	}
}