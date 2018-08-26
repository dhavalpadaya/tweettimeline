<?php
session_start();
/* This file is a callback file so when twitter will send data to this file after authentication */
require_once('lib/TwitterOAuth/src/TwitterOAuth.php');
require 'constant.php'; 
require 'lib/TwitterOAuth/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_REQUEST['oauth_verifier'],$_REQUEST['oauth_token']) && $_REQUEST['oauth_token'] == $_SESSION['oauth_token']){
    $request_token = [];
    $request_token['oauth_token'] = $_SESSION['oauth_token'];
    $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
    $connection->setTimeouts(10, 50);
    $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
    $_SESSION['access_token'] = $access_token;
    // redirect user to timeline.php
    header('Location: timeline.php');
}
