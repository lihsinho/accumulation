<?php
/**
 * Created by PhpStorm.
 * User: lixinhe
 * Date: 2017/12/15
 * Time: 下午6:05
 */

namespace app\common\logic;
use app\common\logic\AliyunOpenApiLogic;
class AliLiveLogic {
    public $Aliyun;
    public function __construct() {
        $this->Aliyun = new AliyunOpenApiLogic();
        $this->Aliyun->vhost            = '';
        $this->Aliyun->live_host        = 'rtmp://video-center.alivecdn.com';
        $this->Aliyun->version          = '2014-11-11';
        $this->Aliyun->appName          = '';
        $this->Aliyun->privateKey       = '';
        $this->Aliyun->format           = 'JSON';
        $this->Aliyun->accessKeyId      = '';
        $this->Aliyun->accessKeySecret  = '';
        $this->Aliyun->domain           = '';
        $this->Aliyun->timeOut = 3600;
    }

    /**
     * 查询在线人数
     * @param $domainName  直播域名
     * @param $appName     应用名
     * @param $streamName  推流名
     * @return bool|int|mixed
     */
    public function describeLiveStreamOnlineUserNum($domainName, $appName, $streamName) {
        $apiParams = array(
            'Action' => 'DescribeLiveStreamOnlineUserNum',
            'DomainName' => $domainName,
            'AppName' => $appName,
            'StreamName' => $streamName,
        );
        return $this->Aliyun->aliApi($apiParams, $credential = "GET", $domain = "cdn.aliyuncs.com");
    }


    /**
     * 获取某个域名或应用下的直播流操作记录
     * @param $appName         应用名
     * @param $streamName      推流名
     * @return bool|int|mixed
     */
    public function describeLiveStreamsControlHistory( $startTime, $endTime) {
        $domainName = $this->Aliyun->vhost;
        $appName = $this->Aliyun->appName;
        $apiParams = array(
            'Action' => 'DescribeLiveStreamsControlHistory',
            'DomainName' => $domainName,
            'AppName' => $appName,
        );
        return $this->aliLive->aliApi($apiParams, $credential = "GET", $this->Aliyun->domain );
    }

    /**
     * 查看指定域名下（或者指定域名下某个应用）的所有正在推的流的信息
     * @return bool|int|mixed
     */
    public function describeLiveStreamsOnlineList() {
        $domainName = $this->Aliyun->vhost;
        $appName = $this->Aliyun->appName;
        $apiParams = array(
            'Action' => 'DescribeLiveStreamsOnlineList',
            'DomainName' => $domainName,
            'AppName' => $appName,
        );
        return $this->Aliyun->aliApi($apiParams, $credential = "GET", $this->Aliyun->domain );
    }

    /**
     * 查询推流黑名单列表
     * @param $domainName       域名
     * @return bool|int|mixed
     */
    public function describeLiveStreamsBlockList($domainName) {
        $apiParams = array(
            'Action' => 'DescribeLiveStreamsBlockList',
            'DomainName' => $domainName,
        );
        return $this->Aliyun->aliApi($apiParams, $credential = "GET", $this->Aliyun->domain );
    }

    /**
     * 生成推流地址
     * @param $streamName 用户专有名
     * @return bool|int|mixed
     */
    public function getPushSteam($streamName) {
        $vhost = $this->Aliyun->vhost;
        $time = time() + $this->Aliyun->timeOut;
        $videohost = $this->Aliyun->live_host;
        $appName = $this->Aliyun->appName;
        $privateKey = $this->Aliyun->privateKey;
        if ($privateKey) {
            $auth_key = md5('/' . $appName . '/' . $streamName . '-' . $time . '-0-0-' . $privateKey);
            $url = $videohost . '/' . $appName . '/' . $streamName . '?vhost=' . $vhost . '&auth_key=' . $time . '-0-0-' . $auth_key;
        } else {
            $url = $videohost . '/' . $appName . '/' . $streamName . '?vhost=' . $vhost;
        }
        return $url;
    }

    /**
     * 生成拉流地址
     * @param $streamName 用户专有名
     * @param $vhost 加速域名
     * @param $type 视频格式 支持rtmp、flv、m3u8三种格式
     * @return bool|int|mixed
     */
    public function getPullSteam($streamName,$type = 'rtmp') {
        $vhost = $this->Aliyun->vhost;
        $time = time() + $this->Aliyun->timeOut;
        $appName = $this->Aliyun->appName;
        $privateKey = $this->Aliyun->privateKey;
        $url = '';
        switch ($type) {
            case 'rtmp':
                $host = 'rtmp://' . $vhost;
                $url = '/' . $appName . '/' . $streamName;
                break;
            case 'flv':
                $host = 'http://' . $vhost;
                $url = '/' . $appName . '/' . $streamName . '.flv';
                break;
            case 'm3u8':
                $host = 'http://' . $vhost;
                $url = '/' . $appName . '/' . $streamName . '.m3u8';
                break;
        }
        if ($privateKey) {
            $auth_key = md5($url . '-' . $time . '-0-0-' . $privateKey);
            $url = $host . $url . '?auth_key=' . $time . '-0-0-' . $auth_key;
        } else {
            $url = $host . $url;
        }
        return $url;
    }

    /**
     * 禁止推流接口
     * @param $domainName        您的加速域名
     * @param $appName          应用名称
     * @param $streamName       流名称
     * @param $liveStareamName  用于指定主播推流还是客户端拉流, 目前支持”publisher” (主播推送)
     * @param $resumeTime       恢复流的时间 UTC时间 格式：2015-12-01T17:37:00Z
     * @return bool|int|mixed
     */
    public function forbid($streamName, $resumeTime, $domainName = 'www.test.com', $appName = 'xnl', $liveStreamType = 'publisher') {
        $apiParams = array(
            'Action' => 'ForbidLiveStream',
            'DomainName' => $domainName,
            'AppName' => $appName,
            'StreamName' => $streamName,
            'LiveStreamType' => $liveStreamType,
            'ResumeTime' => $resumeTime
        );
        return $this->Aliyun->aliApi($apiParams, $credential = "GET", $this->Aliyun->domain );
    }

    /**
     * 恢复直播流推送
     * @param $streamName              流名称
     * @param string $appName 应用名称
     * @param string $liveStreamType 用于指定主播推流还是客户端拉流, 目前支持”publisher” (主播推送)
     * @param string $domainName 您的加速域名
     * @return bool|int|mixed
     */
    public function resumeLive($streamName, $domainName = 'www.test.top', $appName = 'xnl', $liveStreamType = 'publisher') {
        $apiParams = array(
            'Action' => 'ResumeLiveStream',
            'DomainName' => $domainName,
            'AppName' => $appName,
            'StreamName' => $streamName,
            'LiveStreamType' => $liveStreamType,
        );
        return $this->Aliyun->aliApi($apiParams, $credential = "GET", $this->Aliyun->domain);
    }
}