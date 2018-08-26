            $(document).ready(function () {
                $("#search_box").focus(function () {
                    $(this).select();
                });
                $("#search_box_following").focus(function () {
                    $(this).select();
                });
            });

            /*Function will execute when click on Get tweets button or click on follower name in search_autocomplete box*/
            function getTweets(str, split = ',') {
                var user_details = str.split(split);
                var user_name = user_details[0];
                var user_screen_name = user_details[1];
                location.href = '#display_tweets';
                $('#tweet_heading').html(user_name + "'s Recent 10 Tweets.");
                $('#slideshow').html("Loading....");
                var ajaxurl = "get_tweets.php?screen_name=" + user_screen_name;
                $.ajax({
                    type: "GET",
                    url: ajaxurl, //get_follower_tweets.php
                    success: function (response) {
                        document.getElementById("slideshow").innerHTML = "";
                        $('#slideshow').html(response);
                        slider();
                    },
                    error: function () {
                        alert('error in getting user tweets....');
                    }
                });
            }

            var follower_name = new Array();
            var follower_screen = new Array();
            var follower_both = new Array();

            function search_autocomplete()
            {
                $.getJSON('search_followers.php', function (data) {
                    for (jsonObj of data)
                    {
                        if (typeof jsonObj == "object")
                        {
                            //get all follower names in 'follower_name' array
                            $.each(jsonObj, function (key, value) {
                                if (key == "name")
                                    follower_name.push(value);
                            });

                            //get all follower screen_names in 'follower_screen' array
                            $.each(jsonObj, function (key, value) {
                                if (key == "screen_name")
                                    follower_screen.push(value);
                            });
                        }
                    }
                    for (var i = 0; i < follower_name.length; i++)
                    {
                        var both = follower_name[i] + " @" + follower_screen[i];
                        follower_both.push(both);
                    }
                });
            }
            search_autocomplete();          //call function

            $(function () {
                $("#search_box").autocomplete({
                    source: follower_both, //get data from 'follower_both' array for autocomplete textbox
                    select: function (event, ui) {
                        both_names = ui.item.value;
                        getTweets(both_names, ' @');               //call function
                    }
                });
            });

            var following_name = new Array();
            var following_screen = new Array();
            var following_both = new Array();

            function following_autocomplete()
            {
                $.getJSON('search_following.php', function (data) {
                    for (jsonObj of data)
                    {
                        if (typeof jsonObj == "object")
                        {
                            //get all follower names in 'following_name' array
                            $.each(jsonObj, function (key, value) {
                                if (key == "name")
                                    following_name.push(value);
                            });

                            //get all follower screen_names in 'following_screen' array
                            $.each(jsonObj, function (key, value) {
                                if (key == "screen_name")
                                    following_screen.push(value);
                            });
                        }
                    }
                    for (var i = 0; i < following_name.length; i++)
                    {
                        var both1 = following_name[i] + " @" + following_screen[i];
                        following_both.push(both1);
                    }
                });
            }
            following_autocomplete();          //call function

            $(function () {
                $("#search_box_following").autocomplete({
                    source: following_both, //get data from 'following_both' array for autocomplete textbox
                    select: function (event, ui) {
                        following_both_names = ui.item.value;
                        getTweets(following_both_names, ' @');               //call function
                    }
                });
            });