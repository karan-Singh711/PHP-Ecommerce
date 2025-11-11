<?php
require_once __DIR__ . '/../vendor/autoload.php';
 $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
 $dotenv->load();

$params = [
    'client_id'=>$_ENV['CLIENT_ID'],
    'redirect_uri'=>$_ENV['REDIRECT_URL'],
    'response_type'=>'code',
    'scope'=>'email profile',
    'access_type'=>'offline',
    'prompt'=>'consent',
];

$google_url = 'https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query($params);
header('Location:'. $google_url);
?>