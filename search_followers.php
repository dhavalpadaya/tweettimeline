<?php
/* This file will create json data of user's followers which will be used for autocomplete of textbox in 'data1.php'*/
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
$followers_total = 0;
$followers_details = array();
while ($cursor != 0) {
    /* pull followers ID numbers, 100 at a time
       docs: https://dev.twitter.com/docs/api/1.1/get/followers/ids
     */
    if (isset($connection))
    {
        $followers_ids = $connection->get("followers/ids", ['stringify_ids' => true, 'count' => 100, 'cursor' => $cursor]);
        if (isset($followers_ids))
        {
            if (!is_object($followers_ids) || isset($followers_ids->errors_ids)) {
                exit (-1);
            }
        $ids = implode(',', $followers_ids->ids);
        $cursor = $followers_ids->next_cursor_str;
        $followers_total += count($followers_ids->ids);
        }
    /* pull follower details, 100 at a time, using GET
       docs: https://dev.twitter.com/docs/api/1.1/get/users/lookup
     */
    $followers = $connection->get("users/lookup", ['user_id' => $ids]);
    }
    if (isset($followers))
    {
        foreach ($followers as $f)
        {
        array_push($followers_details, array("id"=>$f->id, "name"=>$f->name, "screen_name"=>$f->screen_name));
        //push follower's details in $followers_details array
        }
    } else
    {
        echo "Coundn't find followers this time,Try again later";
        break;
    }
}
$output = json_encode($followers_details);
echo $output;
