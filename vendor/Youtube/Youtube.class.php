<?php

namespace Youtube;

class Youtube
{
    const ENDPOINT_CHANNEL    = '/channels';
    const ENDPOINT_PLAYLIST   = '/playlistItems';
    const ENDPOINT_VIDEOS     = '/videos';
    const TIMEOUT_DEFAULT_SEC = 30;

    protected $_api_root = 'https://www.googleapis.com/youtube/v3';
    protected $_apy_key;

    public function __construct($apy_key)
    {
        $this->_apy_key = $apy_key;
    }

    public function getUserProfile($username)
    {
        $endpoint = $this->_api_root . self::ENDPOINT_CHANNEL . '?key=' . $this->_apy_key . '&id=' . $this->getChannelId($username) . '&part=snippet,contentDetails,statistics,brandingSettings';
        $request  = $this->_executeRequest($endpoint);

        $json = json_decode($request);

        $params = array(
            'title'         => $json->items[0]->snippet->title,
            'img_profile'   => $json->items[0]->snippet->thumbnails->high->url,
            'playlist'      => $json->items[0]->contentDetails->relatedPlaylists->uploads,
            'subscribers'   => number_format($json->items[0]->statistics->subscriberCount, 0, ',', '.'),
            'videos'        => number_format($json->items[0]->statistics->videoCount, 0, ',', '.'),
            'banner'        => $json->items[0]->brandingSettings->image->bannerTabletExtraHdImageUrl
        );

        return $params;
    }

    public function getVideoInfo($video)
    {
        $endpoint = $this->_api_root . self::ENDPOINT_VIDEOS . '?key=' . $this->_apy_key . '&id=' . $video . '&part=snippet,statistics,contentDetails,player';

        return $this->_executeRequest($endpoint);
    }

    public function getUserVideos($playlist, $results, $nextPageToken = null)
    {
        $nextPage = (!isset($nextPageToken)) ? '' : $nextPageToken;

        $endpoint = $this->_api_root . self::ENDPOINT_PLAYLIST . '?key=' . $this->_apy_key . '&playlistId=' . $playlist . '&part=snippet&maxResults=' . $results . '&pageToken=' . $nextPage;
        $request  = $this->_executeRequest($endpoint);

        $response = json_decode($request);
        $token    = array(
            "nextPageToken" => $response->nextPageToken
        );
        $ids = array(
            "ids" => array()
        );

        foreach ($response->items as $video) {
            array_push($ids['ids'], $video->snippet->resourceId->videoId);
        }

        $results = array_merge($token, $ids);

        return $results;
    }

    private function getChannelId($username)
    {
        $endpoint = $this->_api_root . self::ENDPOINT_CHANNEL . '?key=' . $this->_apy_key . '&forUsername=' . $username . '&part=contentDetails';
        $request  = $this->_executeRequest($endpoint);

        $id = json_decode($request);

        return $id->items[0]->id;
    }

    protected function _executeRequest($url)
    {
        $user_agent = "Youtube API/PHP (App {$this->_apy_key})";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json', 'Content-Type: multipart/form-data', 'Expect:' ));
        curl_setopt($ch, CURLOPT_TIMEOUT, self::TIMEOUT_DEFAULT_SEC);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
        curl_setopt($ch, CURLOPT_HTTPGET, 1);

        $response = curl_exec($ch);
        return $response;

        curl_close($ch);
    }
}