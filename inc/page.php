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

$pageToken  = (!empty($_GET['pageToken'])) ? $_GET['pageToken'] : '';

$youtube   = new Youtube($config->get('youtube.apiKey'));
$profile   = $youtube->getUserProfile($config->get('youtube.user'));
$playlist  = $youtube->getUserVideos($profile['playlist'], '12', $pageToken);
$videos    = '';

$videosTpl = new Template("../tpl/shelf-items.tpl");

$videosId = $playlist['ids'];
$token    = $playlist['nextPageToken'];

foreach($videosId as $videoId) {
	$videosTpl->set('id', $videoId);

	$videos .= $videosTpl->output();
}

echo '<div class="shelf clearfix">';
echo $videos;
echo '<a class="load-more" data-next-page-id="'. $token .'" href="#">Load more</a>';
echo '</div>';