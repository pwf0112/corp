<?php
namespace Corp\Msg;

use Corp\Config;
use Corp\Uri;
use Corp\Traits;

class Send
{
	use Traits;

	public $invalid;

	private $url;
    private $user;
    private $party;
    private $tag;
    private $agentId = 0;
    private $safe    = 0;

    public function user($userIds)
    {
        $this->user = $this->initParam($userIds);
        return $this;
    }

    public function party($partyList)
    {
        $this->party = $this->initParam($partyList);
        return $this;
    }

    public function tag($tagList)
    {
        $this->tag = $this->initParam($tagList);
        return $this;
    }

    public function agent($id)
    {
        $this->agentId = $id;
        return $this;
    }

    public function safe($safe = 1)
    {
        $this->safe = $safe;
        return $this;
    }

    public function text($content)
    {
		$json = $this->initJson();

        $json['msgtype'] = 'text';
        $json['text']    = [
            'content' => $content
        ];

        return $this->request($json);
    }

    public function image($mediaId)
    {
		$json = $this->initJson();

        $json['msgtype'] = 'image';
        $json['image']['media_id'] = $mediaId;

        return $this->request($json);
    }

    public function voice($mediaId)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'voice';
        $json['voice']['media_id'] = $mediaId;

        return $this->request($json);
    }

    public function video($mediaId, $title, $desc)
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

    public function file($mediaId)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'file';
        $json['file']['media_id'] = $mediaId;

        return $this->request($json);
    }

    public function news(array $articles)
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

    public function mpNews($news)
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

    private function initJson()
    {
        $this->url = sprintf(Uri::MSG_SEND, Config::$token);

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

    private function request($json)
	{
		$res = self::httpsPost($this->url, $json);

		$invalid['user'] = isset($res->invaliduser) ? explode('|', $res->invaliduser) : null;
		$invalid['party'] = isset($res->invalidparty) ? explode('|', $res->invalidparty) : null;
		$invalid['tag'] = isset($res->invalidtag) ? explode('|', $res->invalidtag) : null;
		
		$this->invalid = $invalid;

		return true;
	}
}