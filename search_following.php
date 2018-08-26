<?php
/* This file will create json data of user's followings which will be used for autocomplete of textbox in 'data1.php'*/
session_start();
require 'lib/TwitterOAuth/autoload.php';
require 'constant.php'; /* Contains constant values like CONSUMER KEY,CONSUMER_SECRET and OAUTH_CALLBACK */
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_SESSION['access_token']))
{
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $connection->setTimeouts(10, 50);
}
$cursor = -1; // first page
$followings_total = 0;
$followings_details = array();
while ($cursor != 0) {
    /* pull followings ID numbers, 100 at a time
       docs: https://dev.twitter.com/docs/api/1.1/get/followers/ids
     */
    if (isset($connection))
    {
        $followings_ids = $connection->get("friends/ids", ['stringify_ids' => true, 'count' => 100, 'cursor' => $cursor]);
        if (isset($followings_ids))
        {
            if (!is_object($followings_ids) || isset($followings_ids->errors)) {
                exit (-1);
            }
        $ids = implode(',', $followings_ids->ids);
        $cursor = $followings_ids->next_cursor_str;
        $followings_total += count($followings_ids->ids);
        }
    /* pull followings details, 100 at a time, using GET
       docs: https://dev.twitter.com/docs/api/1.1/get/users/lookup
     */
    $followings = $connection->get("users/lookup", ['user_id' => $ids, 'skip_status' => true]);
    }
    if (isset($followings))
    {
        foreach ($followings as $f)
        {
        array_push($followings_details, array("id"=>$f->id, "name"=>$f->name, "screen_name"=>$f->screen_name));
        //push follower's details in $followers_details array
        }
    } else
    {
        echo "Coundn't find followings this time,Try again later";
        break;
    }
}
$output = json_encode($followings_details);
echo $output;
