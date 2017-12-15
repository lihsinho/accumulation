<?php
/**
 * Created by PhpStorm.
 * User: lixinhe
 * Date: 2017/12/15
 * Time: 下午7:58
 */

namespace app\common\logic;
use app\common\logic\AliyunOpenApiLogic;
use Alilive\Aliyun;
class AliVideoLogic {
    public $Aliyun;
    public function __construct() {
        $this->Aliyun = new AliyunOpenApiLogic();
        $this->Aliyun->version = '2017-03-21';
        $this->Aliyun->format = 'JSON';
        $this->Aliyun->accessKeyId = '';
        $this->Aliyun->accessKeySecret = '';
        $this->Aliyun->domain = 'vod.cn-shanghai.aliyuncs.com';
        $this->Aliyun->timeOut = 3600;
    }

    /**
     * @name 获取点播列表
     * @author lixinhe
     * @return bool|int|mixed
     */
    public function get_video_list() {
        $apiParams = array(
            'Action' => 'GetVideoList',
            'Status' => '',
            'StartTime' => '',
            'EndTime' => '',
            'CateId' => '',
            'PageNo' => '',
            'PageSize' => '',
            'SortBy' => '',
        );
        return $this->Aliyun->aliApi($apiParams, $credential = "GET", $this->Aliyun->domain);
    }

    /**
     * @name 获取视频播放地址
     * @author lixinhe
     * @param $VideoId
     * @return bool|int|mixed
     */
    public function get_play_info($VideoId) {
        $apiParams = array(
            'Action' => 'GetPlayInfo',
            'VideoId' => $VideoId,
            'Formats' => 'mp4,m3u8,mp3',
            'AuthTimeout' => $this->Aliyun->timeOut,
            'StreamType' => 'video,audio',
            'Definition' => '',
        );
        return $this->Aliyun->aliApi($apiParams, $credential = "GET", $this->Aliyun->domain);
    }


}