<?php
/* This file will get logged in user's tweets and create pdf from of those tweets */
/* This file will be called when user click on 'Download All Tweets' button in 'data1.php'*/
ini_set('memory_limit', '-1');                  //to overcome memory issues while creating pdf with large number of tweets
session_start();
require 'lib/TwitterOAuth/autoload.php';
require 'lib/fpdf181/fpdf.php';	  /* File of fpdf181 library to create pdf */
require 'constant.php';           /* Contains constant values like CONSUMER KEY,CONSUMER_SECRET and OAUTH_CALLBACK */
require 'functions.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if(isset($_SESSION['access_token']))
{
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    $connection->setTimeouts(10, 50);
    $user = $connection->get("account/verify_credentials");
    $user_screen_name = $user->screen_name;
    $user_details = $connection->get("users/show",['screen_name' => $user_screen_name]);
    $count_tweets = null;
    $count_followers = null;
    $count_following = null;
        if(isset($user_details))
        {
        $count_tweets = intval($user_details->statuses_count);			//user's total tweets
        $count_following = intval($user_details->friends_count);		//user's followings
        $count_followers = intval($user_details->followers_count);		//user's followers
        }
	
    $tweets = $connection->get('statuses/user_timeline',['count' => 200,'screen_name' => $user_screen_name]);
    $totalTweets[] = $tweets;
    $page = 0;

        if($count_tweets > 200)
        {
        for($count =200;$count<3200;$count += 200)
        {
        $max = count($totalTweets[$page]) - 1;		//id will be from '0' to 'n-1' and count will return 'n'.so subtracted 1 from that
        $tweets = $connection->get('statuses/user_timeline',['count' => 200,'max_id' => $totalTweets[$page][$max]->id_str,'screen_name' => $user_screen_name]);
        $totalTweets[] = $tweets;
        $page += 1;
        } 
        }
}
else
{
    echo "<script type='text/javascript'>";
    echo "alert('You are not logged in or Some network error.Please try again...');";
    echo "document.location.href='timeline.php';";
    echo "</script>";
}

$pdf = new FPDF();
$pdf->AddPage();														//to add new page	
$pageWidth = $pdf->GetPageWidth();

function BoldFont16(){
    global $pdf;
    $pdf->SetFont('Arial','B',16);
}
function BoldFont12(){
    global $pdf;
    $pdf->SetFont('Arial','B',12);
}
function NormalFont12(){
    global $pdf;
    $pdf->SetFont('Arial','',12);
}
BoldFont16();
if(isset($user_details->name))
{
$pdf->Cell(0,10,$user_details->name."'s Tweets",0,0,'C');				//width = 0 means width in full page
$pdf->Ln();
$pdf->Ln();
BoldFont12();
$pdf->Cell(50,10,"Total Tweets");
$pdf->Cell(10,10,":- ");
NormalFont12();
$pdf->Cell(50,10,$count_tweets);
$pdf->Ln();
BoldFont12();
$pdf->Cell(50,10,"Total Followings");
$pdf->Cell(10,10,":- ");
NormalFont12();
$pdf->Cell(50,10,$count_following);
$pdf->Ln();
BoldFont12();
$pdf->Cell(50,10,"Total followers");
$pdf->Cell(10,10,":- ");
NormalFont12();
$pdf->Cell(50,10,$count_followers);
}
$pdf->Ln();
$pdf->Ln();
$count = 1;
if(isset($totalTweets))
{
    foreach ($totalTweets as $page) 
    {
        if(isset($page))
        {
        foreach ($page as $key) 
        {
            if(isset($key->user->name))
            {
            BoldFont12();
            $pdf->Write(10,$count.".".$key->user->name);

            NormalFont12();
            $pdf->Write(10," @".$key->user->screen_name);
                if(isset($key->created_at))
                {
                        $formatted_date = date_formate($key->created_at);
                        $pdf->Write(10," - ".$formatted_date);
                }
            $pdf->Ln();
                if(isset($key->text))
                {
                    if($key->lang == "en")
                    {
                        $tweet = str_replace("\n", " ", $key->text);
                        $pdf->Write(10,$tweet);
                    }
                    else
                    {
                        $pdf->Write(10,"---This Tweet is not in English,So Can't be displayed.---");
                    }
                $pdf->Ln();        
                }
            }
        $count++;
            }
        }
    }
}
else
{
    $pdf->Write(10, 'Can not get data this time,Please Refresh this page or try again later....');
}
$pdf->Output();
