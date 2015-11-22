<?php

require_once '../vendor/autoload.php';

$helperLoader   = new SplClassLoader('Helpers', '../vendor');
$youtubeLoader  = new SplClassLoader('Youtube', '../vendor');

$helperLoader->register();
$youtubeLoader->register();

use Helpers\Config;
use Youtube\Youtube;

$config = new Config;
$config->load('../config/config.php');

$youtube = new Youtube($config->get('youtube.apiKey'));

header('Content-type: application/json');

if (!isset($_GET['id'])) {
    $data = array(
        'status'      => 'error',
        'description' => 'Provide a valid video id.'
    );

    echo json_encode($data);
} else {
    echo $youtube->getVideoInfo($_GET['id']);
}