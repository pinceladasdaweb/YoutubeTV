<?php

require_once '../vendor/autoload.php';

$helperLoader   = new SplClassLoader('Helpers', '../vendor');
$youtubeLoader  = new SplClassLoader('Youtube', '../vendor');
$templateLoader = new SplClassLoader('Broculo', '../vendor');

$helperLoader->register();
$youtubeLoader->register();
$templateLoader->register();

use Helpers\Config;
use Youtube\Youtube;
use Broculo\Template;

$config = new Config;
$config->load('../config/config.php');

$youtube  = new Youtube($config->get('youtube.apiKey'));
$profile  = $youtube->getUserProfile($config->get('youtube.user'));
$playlist = $youtube->getUserVideos($profile['playlist'], $config->get('youtube.maxResults'));
$featured = $youtube->getVideoInfo($playlist[0]);
$videoId  = json_decode($featured);
$videos   = '';

unset($playlist[0]);

$brandedTpl  = new Template("../tpl/branded.tpl");
$featuredTpl = new Template("../tpl/featured.tpl");
$videosTpl   = new Template("../tpl/shelf-items.tpl");

$brandedTpl->set("src", $profile['banner']);
$brandedTpl->set("title", $profile['title']);
$brandedTpl->set("img_profile", $profile['img_profile']);
$brandedTpl->set("subscribers", $profile['subscribers']);
$brandedTpl->set("videos", $profile['videos']);

$featuredTpl->set("id", $videoId->items[0]->id);

foreach($playlist as $video) {
    $videosTpl->set('id', $video);

    $videos.= $videosTpl->output();
}

echo $brandedTpl->output();
echo $featuredTpl->output();
echo '<div class="shelf clearfix">' . $videos . '</div>';