<?php
namespace Corp\Message;

use Corp\Base;
use Corp\Uri;

class Send
{
    private $url;

    private $user;
    private $party;
    private $tag;
    private $agentId;
    private $safe;

    public function __construct()
    {
        $this->safe = 0;
    }

    public function token($token)
    {
        $this->url = sprintf(Uri::MSG_SEND, $token);
        return $this;
    }

    public function user($userIds)
    {
        $this->user = $this->initParam($userIds);
        return $this;
    }

    public function party(array $partyList)
    {
        $this->party = $this->initParam($partyList);
        return $this;
    }

    public function tag(array $tagList)
    {
        $this->tag = $this->initParam($tagList);
        return $this;
    }

    public function agent($id)
    {
        $this->agentId = $id;
        return $this;
    }

    public function safe()
    {
        $this->safe = 1;
        return $this;
    }

    public function text($content)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'text';
        $json['text']    = [
            'content' => $content
        ];

        return Base::postHttpsApi($this->url, $json);
    }

    public function image($mediaId)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'image';
        $json['image']['media_id'] = $mediaId;

        return Base::postHttpsApi($this->url, $json);
    }

    public function voice($mediaId)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'voice';
        $json['voice']['media_id'] = $mediaId;

        return Base::postHttpsApi($this->url, $json);
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

        return Base::postHttpsApi($this->url, $json);
    }

    public function file($mediaId)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'file';
        $json['file']['media_id'] = $mediaId;

        return Base::postHttpsApi($this->url, $json);
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

        return Base::postHttpsApi($this->url, $json);
    }

    public function mpNews($news)
    {
        $json = $this->initJson();

        $json['msgtype'] = 'mpnews';
        if (is_array($news)) {
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
        } else {
            $json['mpnews']['media_id'] = $news;
        }

        return Base::postHttpsApi($this->url, $json);
    }

    private function initJson()
    {
        if (empty($this->user) && empty($this->party) && empty($this->tag)) {
            $this->user = '@all';
        }

        return [
            'touser' => $this->user,
            'toparty' => $this->party,
            'totag' => $this->tag,
            'agentid' => $this->agentId,
            'safe' => $this->safe
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
}