<?php
namespace Corp\Kf;

use Corp\Base;
use Corp\Exception;
use Corp\Traits;
use Corp\Uri;

/**
 * 企业号客服类
 */
class Internal
{
	use Traits;

	/**
	 * @var string 客服 ACCESS_TOKEN 值
	 */
	private $token;
	/**
	 * @var string 客服UserID
	 */
	private $kf;
	/**
	 * @var string 用户UserID
	 */
	private $userId;

	/**
	 * 创建企业号客服消息服务
	 */
	public function __construct()
	{
		$this->token = Base::$kfToken;
	}

	/**
	 * 设置企业号客服 ACCESS_TOKEN
	 * @param string $token 企业号客服 ACCESS_TOKEN
	 * @return $this
	 */
	public function setToken($token)
	{
		$this->token = $token;
		return $this;
	}

	/**
	 * 设置客服UserID
	 * @param string $kf 客服UserID
	 * @return $this
	 */
	public function setKf($kf)
	{
		$this->kf = $kf;
		return $this;
	}

	/**
	 * 设置客服请求用户UserID
	 * @param string $userId 客服请求用户UserID
	 * @return $this
	 */
	public function setUser($userId)
	{
		$this->userId = $userId;
		return $this;
	}

	/**
	 * 接收文本类型的客服请求消息
	 * @param string $content 文本内容
	 * @return bool
	 * @throws Exception
	 */
	public function receiveText($content)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'text';
        $data->json['text']['content'] = $content;

        return self::httpsPost($data->url, $data->json)->errcode === 0;
    }

	/**
	 * 接收图片类型的客服请求消息
	 * @param string $mediaId 企业号图片媒体ID
	 * @return bool
	 * @throws Exception
	 */
	public function receiveImage($mediaId)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'image';
        $data->json['image']['media_id'] = $mediaId;

        return self::httpsPost($data->url, $data->json)->errcode === 0;
    }

	/**
	 * 接收文件类型的客服请求消息
	 * @param string $mediaId 企业号文件媒体ID
	 * @return bool
	 * @throws Exception
	 */
	public function receiveFile($mediaId)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'file';
        $data->json['file']['media_id'] = $mediaId;

        return self::httpsPost($data->url, $data->json)->errcode === 0;
    }

	/**
	 * 接收语音类型的客服请求消息
	 * @param string $mediaId 企业号语音媒体ID
	 * @return bool
	 * @throws Exception
	 */
	public function receiveVoice($mediaId)
    {
        $data = $this->initReceiveData();

        $data->json['msgtype'] = 'voice';
        $data->json['voice']['media_id'] = $mediaId;

        return self::httpsPost($data->url, $data->json)->errcode === 0;
    }

	/**
	 * @return \stdClass
	 */
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
        $res->url  = $url;
        $res->json = $json;

        return $res;
    }
}