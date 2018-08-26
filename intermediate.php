<?php
session_start();
require 'lib/TwitterOAuth/autoload.php'; /* This file is from TwitterOAuth library */
require 'constant.php'; /* Contains constant values like CONSUMER KEY,CONSUMER_SECRET and OAUTH_CALLBACK */
use Abraham\TwitterOAuth\TwitterOAuth;

if (!isset($_SESSION['access_token'])) {										//for new log in
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    $connection->setTimeouts(10, 50);
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

    $_SESSION['oauth_token'] = $request_token['oauth_token'];
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
    $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        header("Location: $url "); //user will be redirected to generated url
} else {																		//for already logged in user
    header('Location: timeline.php');
}

