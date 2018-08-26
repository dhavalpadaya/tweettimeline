<?php
session_start();
require 'lib/TwitterOAuth/autoload.php';
require 'constant.php'; 
require 'functions.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if(isset($_SESSION['access_token']))
{
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $connection->setTimeouts(50, 50);
    $user = $connection->get("account/verify_credentials");
        $screen_name = $_GET['screen_name'];
    $tweets = $connection->get('statuses/user_timeline',['count' => 20,'include_rts' => false,'screen_name' => $screen_name]);
    if(isset($tweets))
    {
        displayTweets($tweets);               /* To display follower's tweets (This function is in file functions.php) */
    }
}
else
{
    echo "<div>";
    echo "Can't fetch Tweets of this user.Please Refresh this page or try again later.";
    echo "</div>";
}
