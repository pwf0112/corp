<?php
namespace Corp;

class Message extends Manager
{
	use Traits;

	/**
	 * @var array 无效的接收者列表
	 */
	private $invalid;
	/**
	 * @var string 企业号发送消息接口地址
	 */
	private $url;
	/**
	 * @var string 消息接收用户ID列表 |分割
	 */
	private $user;
	/**
	 * @var string 消息接收部门列表 |分割
	 */
	private $party;
	/**
	 * @var string 消息接收用户Tag列表 |分割
	 */
	private $tag;
	/**
	 * @var int 消息接收企业号应用ID
	 */
	private $agentId = 0;
	/**
	 * @var int 是否安全加密发送
	 */
	private $safe    = 0;

	/**
	 * 设置接收者用户(列表)
	 * @param string|array $userIds 用户ID或者用户ID数组
	 * @return $this
	 */
	public function setUser($userIds)
    {
        $this->user = $this->initParam($userIds);
        return $this;
    }

	/**
	 * 设置接收者部门(列表)
	 * @param int|array $partyList 部门ID或者部门ID数组
	 * @return $this
	 */
	public function setParty($partyList)
    {
        $this->party = $this->initParam($partyList);
        return $this;
    }

	/**
	 * 设置接收者标签(列表)
	 * @param int|array $tagList 通讯录标签ID或者标签ID数组
	 * @return $this
	 */
	public function setTag($tagList)
    {
        $this->tag = $this->initParam($tagList);
        return $this;
    }

	/**
	 * 设置接收应用ID
	 * @param int $id
	 * @return $this
	 */
	public function setAgent($id)
    {
        $this->agentId = $id;
        return $this;
    }

	/**
	 * 是否安全加密消息
	 * @param bool $safe
	 * @return $this
	 */
	public function setSafe($safe = true)
    {
        $this->safe = intval($safe);
        return $this;
    }

	/**
	 * 发送文本消息
	 * @param string $content 文本内容
	 * @return bool 如果存在无效发送返回False,否则返回True
	 */
	public function sendText($content)
    {
		$json = $this->initJson();

        $json['msgtype'] = 'text';
        $json['text']    = [
            'content' => $content
        ];

        return $this->request($json);
    }

	/**
	 * 发送图片消息
	 * @param string $mediaId 企业号图片媒体ID
	 * @return bool 如果存在无效发送返回False,否则返回True
	 */
	public function sendImage($mediaId)
    {
		$json = $this->initJson();

        $json['msgtype'] = 'image';
        $json['image']['media_id'] = $mediaId;

        return $this->request($json);
    }

	/**
	 * 发送语音消息
	 * @param string $mediaId 企业号语音媒体ID
	 * @return bool 如果存在无效发送返回False,否则返回True
	 */
	public function sendVoice($mediaId)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'voice';
        $json['voice']['media_id'] = $mediaId;

        return $this->request($json);
    }

	/**
	 * 发送视频消息
	 * @param string $mediaId 企业号视频媒体ID
	 * @param string $title 视频标题
	 * @param string $desc 视频描述
	 * @return bool 如果存在无效发送返回False,否则返回True
	 */
	public function sendVideo($mediaId, $title, $desc)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'video';
        $json['video'] = [
            'media_id' => $mediaId,
            'title' => $title,
            'description' => $desc
        ];

        return $this->request($json);
    }

	/**
	 * 发送文件
	 * @param string $mediaId 文件媒体ID
	 * @return bool 如果存在无效发送返回False,否则返回True
	 */
	public function sendFile($mediaId)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'file';
        $json['file']['media_id'] = $mediaId;

        return $this->request($json);
    }

	/**
	 * 发送News消息
	 * @param array $articles [[title, desc, picUrl, url], ...]
	 * @return bool 如果存在无效发送返回False,否则返回True
	 */
	public function sendNews(array $articles)
    {
        $items = [];
        foreach ($articles as $article) {
            $items[] = [
                'title' => $article[0],
                'description' => $article[1],
                'picurl' => $article[2],
                'url' => $article[3]
            ];
        }

        $json = $this->initJson();
        $json['msgtype'] = 'news';
        $json['news']['articles'] = $items;
        unset($json['safe']);

        return $this->request($json);
    }

	/**
	 * 发送MpNews消息
	 * @param string|array $news 图文消息媒体ID或者企业号图文消息数据二维数组
	 *                           title|thumb|content|[author|source|digest(描述)|cover]
	 * @return bool 如果存在无效发送返回False,否则返回True
	 */
	public function sendMpNews($news)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'mpnews';
        if (is_array($news)) {
            $item = [];

			self::makeNews($news, $item);

            $json['mpnews']['articles'] = $item;
        } else {
            $json['mpnews']['media_id'] = $news;
        }

        return $this->request($json);
    }

	/**
	 * @return array
	 */
	private function initJson()
    {
        $this->url = sprintf(Uri::MSG_SEND, $this->token);

        if (empty($this->user) && empty($this->party) && empty($this->tag)) {
            $this->user = '@all';
        }

        return [
            'touser'  => $this->user,
            'toparty' => $this->party,
            'totag'   => $this->tag,
            'agentid' => $this->agentId,
            'safe'    => $this->safe
        ];
    }

	/**
	 * @param $data
	 * @return null|string
	 */
	private function initParam($data)
    {
        $res = null;

        if (!empty($data)) {
            if (is_string($data)) {
                $res = $data;
            } else {
                $res = implode('|', $data);
            }
        }

        return $res;
    }

	/**
	 * @param $json
	 * @return bool
	 */
	private function request($json)
	{
		$res = self::httpsPost($this->url, $json);

		$invalid['user'] = isset($res->invaliduser) ? explode('|', $res->invaliduser) : null;
		$invalid['party'] = isset($res->invalidparty) ? explode('|', $res->invalidparty) : null;
		$invalid['tag'] = isset($res->invalidtag) ? explode('|', $res->invalidtag) : null;
		
		$this->invalid = $invalid;

		return is_null($invalid['user']) || is_null($invalid['party']) || is_null($invalid['tag']);
	}

	public function getInvalid()
	{
		return $this->invalid;
	}
}