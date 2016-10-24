<?php
namespace Corp;

class Resp
{
	private $userId;
	private $corpId;
	private $time;

	public function __construct()
	{
		$this->time = time();
		$this->corpId = Config::$corpId;
	}

	public function toUser($userId)
	{
		$this->userId = $userId;
		return $this;
	}

	public function text($content)
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

	public function image($mediaId)
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

	public function voice($mediaId)
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

	public function video($mediaId, $title, $description)
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
       <Description><![CDATA[{$description}]]></Description>
   </Video>
</xml>
data;
	}

	public function news($articles)
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