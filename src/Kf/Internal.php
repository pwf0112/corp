<?php
namespace Corp\Kf;

use Corp\Base;
use Corp\Traits;
use Corp\Uri;

class Internal
{
	use Traits;

	private $token;
	private $kf;
	private $userId;

	public function __construct()
	{
		$this->token = Base::$kfToken;
	}

	public function setToken($token)
	{
		$this->token = $token;
		return $this;
	}

	public function setKf($kf)
	{
		$this->kf = $kf;
		return $this;
	}

	public function setUser($userId)
	{
		$this->userId = $userId;
		return $this;
	}

    public function receiveText($content)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'text';
        $data->json['text']['content'] = $content;

        return self::httpsPost($data->url, $data->json);
    }

    public function receiveImage($mediaId)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'image';
        $data->json['image']['media_id'] = $mediaId;

        return self::httpsPost($data->url, $data->json);
    }

    public function receiveFile($mediaId)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'file';
        $data->json['file']['media_id'] = $mediaId;

        return self::httpsPost($data->url, $data->json);
    }

    public function receiveVoice($mediaId)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'voice';
        $data->json['voice']['media_id'] = $mediaId;

        return self::httpsPost($data->url, $data->json);
    }

    private function initReceiveData()
    {
        $url = sprintf(Uri::KF_SEND, $this->token);

        $json['sender'] = [
            'type' => 'userid',
            'id' => $this->userId
        ];

        $json['receiver'] = [
            'type' => 'kf',
            'id' => $this->kf
        ];

        $res = new \stdClass;
        $res->url = $url;
        $res->json = $json;

        return $res;
    }
}