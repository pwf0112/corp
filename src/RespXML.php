<?php
namespace Corp;

class RespXML
{
	/**
	 * @var string 企业号通讯录用户ID
	 */
	private $userId;
	/**
	 * @var string 企业号ID
	 */
	private $corpId;
	/**
	 * @var int 随机时间戳
	 */
	private $time;

	/**
	 * 创建企业号被动响应明文XML生成器
	 */
	public function __construct()
	{
		$this->time = time();
		$this->corpId = Base::$corpId;
	}

	/**
	 * 设置被动响应消息接收用户ID
	 * @param string $userId 用户ID
	 * @return $this
	 */
	public function setToUser($userId)
	{
		$this->userId = $userId;
		return $this;
	}

	/**
	 * 生成Text被动响应XML字符串
	 * @param string $content 消息文本内容
	 * @return string
	 */
	public function generateText($content)
	{
		return <<<data
<xml>
   <ToUserName><![CDATA[{$this->userId}]]></ToUserName>
   <FromUserName><![CDATA[{$this->corpId}]]></FromUserName> 
   <CreateTime>{$this->time}</CreateTime>
   <MsgType><![CDATA[text]]></MsgType>
   <Content><![CDATA[$content]]></Content>
</xml>
data;
	}

	/**
	 * 生成Image被动响应XML字符串
	 * @param string $mediaId Image media_id
	 * @return string
	 */
	public function generateImage($mediaId)
	{
		return <<<data
<xml>
   <ToUserName><![CDATA[{$this->userId}]]></ToUserName>
   <FromUserName><![CDATA[{$this->corpId}]]></FromUserName>
   <CreateTime>{$this->time}</CreateTime>
   <MsgType><![CDATA[image]]></MsgType>
   <Image>
       <MediaId><![CDATA[{$mediaId}]]></MediaId>
   </Image>
</xml>
data;
	}

	/**
	 * 生成Voice被动响应XML字符串
	 * @param string $mediaId Voice media_id
	 * @return string
	 */
	public function generateVoice($mediaId)
	{
		return <<<data
<xml>
   <ToUserName><![CDATA[{$this->userId}]]></ToUserName>
   <FromUserName><![CDATA[{$this->corpId}]]></FromUserName>
   <CreateTime>{$this->time}</CreateTime>
   <MsgType><![CDATA[voice]]></MsgType>
   <Voice>
       <MediaId><![CDATA[$mediaId]]></MediaId>
   </Voice>
</xml>
data;
	}

	/**
	 * 生成Video被动响应XML字符串
	 * @param string $mediaId Video media_id
	 * @param string $title Video 标题
	 * @param string $desc Video 描述
	 * @return string
	 */
	public function generateVideo($mediaId, $title, $desc)
	{
		return <<<data
<xml>
   <ToUserName><![CDATA[{$this->userId}]]></ToUserName>
   <FromUserName><![CDATA[{$this->corpId}]]></FromUserName>
   <CreateTime>{$this->time}</CreateTime>
   <MsgType><![CDATA[video]]></MsgType>
   <Video>
       <MediaId><![CDATA[{$mediaId}]]></MediaId>
       <Title><![CDATA[{$title}]]></Title>
       <Description><![CDATA[{$desc}]]></Description>
   </Video>
</xml>
data;
	}

	/**
	 * 生成News(图文)被动响应XML字符串
	 * @param array $articles 图文消息数据[[title, desc, picUrl, url], ...]
	 * @return string
	 */
	public function generateNews($articles)
	{
		$articleCount = count($articles);

		$item = '';

		foreach ($articles as $article) {
			$item .= <<<item
<item>
   <Title><![CDATA[{$article[0]}]]></Title> 
   <Description><![CDATA[{$article[1]}]]></Description>
   <PicUrl><![CDATA[{$article[2]}]]></PicUrl>
   <Url><![CDATA[{$article[3]}]]></Url>
</item>
item;
		}

		return <<<data
<xml>
   <ToUserName><![CDATA[{$this->userId}]]></ToUserName>
   <FromUserName><![CDATA[{$this->corpId}]]></FromUserName>
   <CreateTime>{$this->time}</CreateTime>
   <MsgType><![CDATA[news]]></MsgType>
   <ArticleCount>{$articleCount}</ArticleCount>
   <Articles>{$item}</Articles>
</xml>
data;
	}
}