<?php
session_start();
require 'lib/TwitterOAuth/autoload.php';
require 'constant.php'; 
require 'functions.php';
use Abraham\TwitterOAuth\TwitterOAuth;

if (isset($_SESSION['access_token']))
{                                                               //for already logged in
    $access_token = $_SESSION['access_token'];
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
    if (isset($connection))
    {
    $connection->setTimeouts(50, 50);
    $user = $connection->get("account/verify_credentials");
    $normal_img_url = $user->profile_image_url_https;
    $img_url = str_replace("_normal.", ".", $normal_img_url);
    $count_followers = intval($user->followers_count);
    $count_following = intval($user->friends_count);
    $count_tweets = intval($user->statuses_count);
    $followers_ids = $connection->get("followers/ids", ['stringify_ids' => true,'count' => 100]);
    if(isset($followers_ids->ids))
    {
    $ids = implode (',', $followers_ids->ids);
    }

    if(isset($ids))
    {
    $followers_result = $connection->get("users/lookup", ['user_id' => $ids,'skip_status' => true]);
    } 
    else
    {
        $followers_result = $connection->get('followers/list', ['count' => 10, 'skip_status' => true]);
    }

    $followings_ids = $connection->get("friends/ids", ['stringify_ids' => true,'count' => 100]);
        if(isset($followings_ids->ids))
        {
        $ids1 = implode (',', $followings_ids->ids);
        }
        if(isset($ids1))
        {
        $followings_result = $connection->get("users/lookup", ['user_id' => $ids1,'skip_status' => true]);
        } 
        else
        {
        $followings_result = $connection->get('friends/list', ['count' => 10, 'skip_status' => true]); 
        }
    }                                   //closing if loop to check $connection is set
    else
    {
        echo "Some networking issues occurs,Couldn't get data this time.Please Try again later..or Refresh this page..";
    }
} else
{
    echo "<script type='text/javascript'>";
    echo "alert('You are not logged in or Some network error.Please try again...');";
    echo "document.location.href='index.php';";
    echo "</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Twitter Timeline</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="Website to see recent 10 tweets of home timeline and tweets of followers and followings.User can also download his/her tweets in pdf formate."/>
<meta name="keywords" content="Twitter Timeline,Get Quick Tweets,Twitter,Download Tweets,twittertimeline.000webhostapp.com/"/>
<meta name="author" content="Dhaval Padaya"/>
<meta name="robots" content="index,follow"/>

<link rel="stylesheet" href="lib/bootstrap.min.css">

<script src="lib/jquery.min.js"></script>
<script src="lib/bootstrap.min.js"></script>

<!--addded for autocomplete -->
<link rel="stylesheet" href="lib/autocomplete-ui/jquery-ui.css">
<script src="lib/autocomplete-ui/jquery-ui.js"></script>
		
<link rel="stylesheet" href="css/slideshow.css">
<link rel="stylesheet" href="css/style.css">
        
<script type="text/javascript" src="js/custom.js"></script>

<link rel="stylesheet" href="css/searchbar.css">

<style type="text/css">
</style>
</head>
<body>
        <div class='container'>

            <!-- Division for heading of webssite -->
            <div class="page-header">
                <h1>Twitter Timeline</h1>
            </div>                          <!-- closing 'page-header' division -->
            <div class="row">                <!-- logout row-->
            <div class="col-sm-12">
            <div class="dropdown" style='float: right;'>
                <a class="btn btn-success btn-lg" href="logout.php">
                    <span class="fa-sign-out"></span> Log out
                </a>
            </div>
            </div>                      <!-- closing division col-sm-12 of logout divition -->
            </div>                      <!-- closing logout row -->
                                              
            <div class="row">                       <!-- first row -->

                <!-- Division For User Profile Starts -->
                <div class="col-sm-4">

                <div class="row">
                    <?php
                    echo "<div class='row'>";
                    if (isset($user->name)) {
                        echo "<h3 class='boxHeading'>".$user->name."'s Profile</h3>";
                    }
                    echo "</div>";
                    ?>
                </div>

                    <?php
                    
                    echo "<div id='profile_box'>"; //
                    echo "<div class='row'>";     
                                echo "<div class='col-sm-3'></div>";
                                echo "<div class='col-sm-6'>";
                                if (isset($img_url))
                                    echo "<img src='".$img_url."' class='media-object' style='height:200px;width:100%;'>";
                                echo "</div>";
                                echo "<div class='col-sm-3'></div>";
                    echo "</div>"; //closing row of image
                    echo "<div class='row'>";
                                echo "<div class='col-sm-12'>";
                                    if (isset($user->name)) {
                                        echo "<a href='data1.php' ><h3 style='text-align:center;'>".$user->name."</h3></a>";
                                    }
                                echo "</div>";
                    echo "</div>"; //closing row division for name
                    echo "<div class='row'>";
                                echo "<div class='col-sm-2'></div>";
                                echo "<div class='cols-sm-8'>";
                                    echo "<table class='' style='width:80%;font-weight:bold;margin-left:15px;'>";
                                        echo "<tbody>";
                                            echo "<tr>";
                                            if (isset($count_followers))
                                            echo "<td><a href='#followers_div'>Followers</a></td><td>".$count_followers."</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                            if (isset($count_following))
                                            echo "<td><a href='#followings_div'>Followings</a></td><td>".$count_following."</td>";
                                            echo "</tr>";
                                            echo "<tr>";
                                            if (isset($count_tweets))
                                            echo "<td>Total Tweets</td><td>".$count_tweets."</td>";
                                            echo "</tr>";
                                        echo "</tbody>";
                                    echo "</table>";
                                echo "</div>";
                                echo "<div class='col-sm-2'></div>";
                    echo "</div>"; //closing row of table
                    echo "<div class='row'>";
                                echo "<div class='col-sm-2'></div>";
                                echo "<div class='col-sm-8'>";
                                echo "<a target='_blank' href='create_pdf.php'><button type='button' class='downloadbutton' style='float:right;margin-top:10px;'>Download Your Tweets</button></a>";
                                echo "</div>";
                                echo "<div class='col-sm-2'></div>";
                    echo "</div>"; //closing row of download button
                    echo "</div>"; //closing #profile_box div
                    ?>
                </div>
                <!-- Division for user Profile Ends -->

                <!-- Division for displaying tweets Start -->
                <div class="col-sm-8" style="border-color:blue;" id="display_tweets">
                    <?php
                    echo "<div class='row'>";
                    if (isset($user->name)) {
                        echo "<h3 class='boxHeading' id='tweet_heading'>Recent 10 tweets on ".$user->name."'s home timeline</h3>";
                    }
                    echo "</div>";
                    $tweets = $connection->get('statuses/home_timeline', ['count' => 20, 'include_rts' => false]);

                    echo "<div id='slideshow'>";
                    if (isset($tweets)) {
                        displayTweets($tweets); /* To display user's home timeline tweets (This function is in file 'functions.php') */
                    }
                    echo "</div>";
                    ?>
                </div>             <!-- closing division 'col-sm-8' in tweets-->
            </div>        <!-- closing first row division -->
                <!-- Division for displaying tweets End -->

            <div class="row" id="followers_div">                   <!-- Second row -->
                
                <!-- Division for displaying followers -->
                <div class="col-sm-6">
                    <?php
                    if (isset($user->name)) {
                        echo "<div class='row'>";
                        echo "<div class='col-sm-12'>";
                        echo "<h3 class='boxHeading'>".$user->name."'s Followers</h3>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ui-widget">
                            <form class="form-wrapper">
                                        <input type="text" id="search_box" class="searchboxclass" placeholder="Search Follower" required>
                            </form>
                            </div>

            <!-- Follower's List Table Start -->
                            <table class="table table-responsive">
                                <tbody>
                                    <?php
                                    $i = 0;
                                    if (isset($followers_result)) 
                                    {
                                        if (is_array($followers_result))
                                        {
                                            shuffle($followers_result);
                                            $followers_get = $followers_result;
                                        } else
                                        {
                                            $followers_get = $followers_result->users;
                                        }
                                        foreach ($followers_get as $follower) {
                                            if ($i<10) 
                                            {
                                                if (isset($follower->profile_image_url_https)) {
                                                    $follower_profile_url = $follower->profile_image_url_https;
                                                }
                                                echo "<tr class='active'>";
                                                if (isset($follower_profile_url)) {
                                                    echo "<td>"."<img src='".$follower_profile_url."' class='media-object' style='height:35px,width:35px;'/>"."</td>";
                                                }
                                                if (isset($follower->name)) {
                                                    echo "<td>".$follower->name;
                                                }
                                                /*if (isset($follower->verified)) {
                                                    if ($follower->verified == 1) {
                                                    echo "<img src='images/verified_twitter.png' class='verified_twitter_img'/>";
                                                    }
                                                }*/
                                                echo "<br/>";
                                                if (isset($follower->screen_name)) {
                                                    echo "@"."<span style='font-weight:lighter'>".$follower->screen_name."</span>"."</td>";
                                                }
                                                if (isset($follower->name) && isset($follower->screen_name)) {
                                                    $follower_details = $follower->name.','.$follower->screen_name;
                                                    echo "<td><a href='#display_tweets'><button class='btn btn-success' type='button' style='margin-left:5px;' onclick='getTweets(\"$follower_details\");'>Get Tweets</button></a></td>";
                                                }
                                                echo "</tr>";
                                                $i++;
                                            } else {
                                                break;
                                            }
                                        }
                                    } else {
                                        echo "Sorry,Can not get Followers details at this time,Please try again later.";
                                    }
                                    ?>
                                </tbody>
                            </table>
            <!-- Follower's List Table End -->

                        </div>              <!-- closing division 'col-sm-4' in follower's tweets -->
                    </div>                  <!-- closing division 'row' in follower's tweets -->
                    </div>           <!-- closing division 'col-sm-6' in follower's tweets -->

<!-- Division for displaying followings -->
<div class="col-sm-6" id="followings_div">
<?php
if (isset($user->name)) {
    echo "<div class='row'>";
    echo "<div class='col-sm-12'>";
    echo "<h3  class='boxHeading'>".$user->name."'s Followings</h3>";
    echo "</div>";
    echo "</div>";
}
?>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="ui-widget">
                            <form class="form-wrapper">
    <input type="text" id="search_box_following" class="searchboxclass" placeholder="Search Following" required>
</form>
                            </div>

                            <!-- Following's List Table Start -->
                            <table class="table table-responsive">
                                <tbody>
<?php
$i = 0;
if (isset($followings_result)) {
    if(is_array($followings_result))
    {
    shuffle($followings_result);
    $followings_get = $followings_result;
    }
    else
    {
    $followings_get = $followings_result->users;
    }
    foreach ($followings_get as $following) {
        if ($i < 10) {
            if (isset($following->profile_image_url_https)) {
                $following_profile_url = $following->profile_image_url_https;
            }
            echo "<tr class='active'>";
            if (isset($following_profile_url)) {
                echo "<td>" . "<img src='" . $following_profile_url . "' class='media-object' style='height:35px,width:35px;'/>" . "</td>";
            }
            if (isset($following->name)) {
                echo "<td>" . $following->name;
            }
            /*if (isset($following->verified)) {
                if ($following->verified == 1) {
                                    echo "<img src='images/verified_twitter.png' class='verified_twitter_img'/>";
                }
            }*/
            echo "<br/>";
            if (isset($following->screen_name)) {
                echo "@"."<span style='font-weight:lighter'>".$following->screen_name."</span>"."</td>";
            }
            if (isset($following->name) && isset($following->screen_name)) {
                $following_details = $following->name.','.$following->screen_name;
                echo "<td><a href='#display_tweets'><button class='btn btn-success' type='button' style='margin-left:10px;' onclick='getTweets(\"$following_details\");'>Get Tweets</button></a></td>";
            }
            echo "</tr>";
            $i++;
        } else {
            break;
        }
    }
} else {
    echo "Sorry,Can not get Your Followings details at this time,Please try again later.";
}
?>
                                </tbody>
                            </table>
        <!-- Following's List Table End -->

                        </div>           <!-- closing dision 'col-sm-12' in followings's tweets -->
                    </div>           <!-- closing division 'row' in followings's tweets -->  
                </div>           <!-- closing division 'cols-sm-4' in followings's tweets -->
                <div class="cols-sm-2"></div>
            </div>          <!-- closing Second row division -->
    </div>          <!-- closing 'container' division -->
</body>
 <script src="js/slideshow.js"></script>             <!-- script for slideshow -->
</html>
