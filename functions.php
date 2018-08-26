<?php
/* This file contains one function 'displayTweets'*/

/* displayTweets() function is used to display Tweets in jQuery slider */
require 'lib/makeLinks.php';
function displayTweets($tweets)
{
$i = 0;
foreach ($tweets as $key) 
    {
    if ($i<=10)
    {
        if (isset($key->user->profile_image_url_https))
        {
        $tweet_profile_url = $key->user->profile_image_url_https;
        }
    echo "<div class='tweetBox'>";
    echo "<div class='media slides' style='height:370px;'>";
        echo "<div class='media-left'>";
        if (isset($tweet_profile_url))
        {
        echo "<img src='".$tweet_profile_url."' class='media-object' style='height:50px,width:50px;'/>";
        }
        echo "</div>";
        echo "<div class='media-body'>";
        if (isset($key->user->name))
        {
        echo "<b style='font-size: 25px;'>".$key->user->name."</b>";
        }
        /*if (isset($key->user->verified))
        {
        if ($key->user->verified == 1)echo "<img src='images/download.png' class='verified_twitter_img' style='height:15px;
  width:15px;'/>";
        }*/
        if (isset($key->user->screen_name))
        {
        echo " @".$key->user->screen_name."&diams;"; //will display black diamond between screen_name of user and date of tweet
        }
        if (isset($key->created_at))										//date of tweet
        {
    $formatted_date = date_formate($key->created_at);
            echo $formatted_date;
        }
        echo "<br/>";
        if (isset($key->text))
        {
        $tweet_and_links = makeLinks($key->text); //This function(lib/makeLinks.php file) is used to find links in tweet and place it in <a> tag 
        }

        if (isset($tweet_and_links))
        {
        echo $tweet_and_links;
        }
        echo "<br/>";
        if (isset($key->entities->media)) {

        foreach ($key->entities->media as $media) {
            echo "<img src='".$media->media_url_https.":small' style='height:280px;width:270px;'/>"; //':small' is written for size, Twitter API provides four sizes - thumb,small,medium and large
        }
            }
            echo "<br/>"; 
        echo "</div>";
    echo "</div>";
    echo "</div>";
    $i++;
    } else
    {
    break;
    }
}
}

function date_formate($dt) {
            //convert date from +0000 timezone to +0530 timezone
            $date = new DateTime($dt);
            $date->setTimezone(new DateTimeZone('Asia/Colombo')); //Srilanka time zone is used because Indian time zone is not provided by twitter
            $changed_date = $date->format('H:i, M d y');
        return $changed_date;
}
