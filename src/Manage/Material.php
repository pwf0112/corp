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
	 * 添加永久企业号图文素材
	 * @param array $news 图文列表 <br>
	 *  图文=[title(标题)|thumb(封面)|content(内容-支持HTML)|?author(作者)|?digest(描述)|?show(是否显示封面)|?source(原文)]
     * @return int 媒体ID
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
	 * 添加永久图片素材
	 * @param string $realPath 图片完整路径
	 * @return int 媒体ID
	 */
	public function addImage($realPath)
	{
		return self::addType($realPath, 'image');
	}

	/**
	 * 添加永久语音素材
	 * @param string $realPath 语音完整路径
	 * @return int 媒体ID
	 */
	public function addVoice($realPath)
	{
		return self::addType($realPath, 'voice');
	}

	/**
	 * 添加永久视频素材
	 * @param string $realPath 视频完整路径
	 * @return int 媒体ID
	 */
	public function addVideo($realPath)
	{
		return self::addType($realPath, 'video');
	}

	/**
	 * 添加永久文件
	 * @param string $realPath 文件完整路径
	 * @return int 媒体ID
	 */
	public function addFile($realPath)
	{
		return self::addType($realPath, 'file');
	}

	/**
     * 获取企业号媒体素材
	 * @param string $mediaId 企业号媒体ID
	 * @return \Curl\Curl|array 当素材类型为MpNews时返回数组，否则返回\Curl\Curl类
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
                $articles = $data['mpnews']['articles'];
				foreach ($articles as $index => $article) {
					$articles[$index]['thumb']  = $article['thumb_media_id'];
					$articles[$index]['source'] = $article['content_source_url'];
					$articles[$index]['show']   = $article['show_cover_pic'];
					unset(
						$articles[$index]['thumb_media_id'],
						$articles[$index]['content_source_url'],
						$articles[$index]['show_cover_pic']
					);
				}
				return $articles;
            }
        } else {
			return $res;
        }
	}

	/**
     * 删除媒体素材
	 * @param string $mediaId 企业号媒体素材ID
	 * @return bool
     * @throws \Corp\Exception
	 */
	public function delete($mediaId)
    {
        return self::httpsGet(Uri::MATERIAL_DEL, [$this->token, $mediaId])->errcode === 0;
	}

	/**
     * 修改永久图文素材
	 * @param string $mediaId 永久图文素材媒体ID
	 * @param array $news 图文列表 <br>
     *  图文=[title(标题)|thumb(封面)|content(内容-支持HTML)|?author(作者)|?digest(描述)|?show(是否显示封面)|?source(原文)]
	 * @return bool
     * @throws \Corp\Exception
	 */
	public function updateMpNew($mediaId, $news)
    {
        $url = sprintf(Uri::MATERIAL_UPDATE_MPNEWS, $this->token);

        $items = [];

		self::makeNews($news, $items);

        $json['media_id'] = $mediaId;
        $json['mpnews']['articles'] = $items;

        return self::httpsPost($url, $json)->errcode === 0;
	}

	/**
     * 获取素材总数统计
	 * @return \stdClass 包含 total|image|voice|video|file|mpnews 属性
     * @throws \Corp\Exception
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
     * 获取企业号图文素材列表
	 * @param int $count 获取数量
	 * @param int $offset 开始偏移量
	 * @return array 返回数据结构参考官方文档
     * @throws \Corp\Exception
	 */
	public function batchMpNews($count = 50, $offset = 0)
    {
        return self::batchType('mpnews', $count, $offset);
	}

	/**
     * 获取企业号图片素材列表
	 * @param int $count 获取数量
	 * @param int $offset 开始偏移量
	 * @return array 返回数据结构参考官方文档
     * @throws \Corp\Exception
	 */
	public function batchImage($count = 50, $offset = 0)
    {
        return self::batchType('image', $count, $offset);
    }

	/**
     * 获取语音素材列表
	 * @param int $count 获取数量
	 * @param int $offset 开始偏移量
	 * @return array 返回数据结构参考官方文档
     * @throws \Corp\Exception
	 */
	public function batchVoice($count = 50, $offset = 0)
    {
        return self::batchType('voice', $count, $offset);
    }

	/**
     * 获取视频素材列表
	 * @param int $count 获取数量
	 * @param int $offset 开始偏移量
	 * @return array 返回数据结构参考官方文档
     * @throws \Corp\Exception
	 */
	public function batchVideo($count = 50, $offset = 0)
    {
        return self::batchType('video', $count, $offset);
    }

	/**
     * 获取文件素材列表
	 * @param int $count 获取数量
	 * @param int $offset 开始偏移量
     * @return array 返回数据结构参考官方文档
     * @throws \Corp\Exception
	 */
	public function batchFile($count = 50, $offset = 0)
    {
        return self::batchType('file', $count, $offset);
    }

	/**
     * 上传普通图文消息内的图片
	 * @param string $realPath 图片完整路径
	 * @return string 上传后图片的URL地址
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

        return self::httpsPost($url, $json)->itemlist;
	}
}