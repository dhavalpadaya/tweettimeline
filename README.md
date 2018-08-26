# tweettimeline
Source code for website [tweettimeline!](http://tweettimeline.000webhostapp.com)

Tweet Timeline is a website which is useful to see user's recent 10 home timeline tweets.This website also display user's followers and followings' names and their tweets.User can also download his/her tweets in pdf format.This website is developed in PHP.

- Features of this website
  - User home timeline tweets.
  - User's Followers and their tweets.
  - User's Followings and their tweets
  - Download tweets As PDF file.

## Libraries Used

### TwitterOAuth
> TwitterOAuth is popular PHP library for Twitter's OAuth REST API.

> Link :- https://github.com/abraham/twitteroauth 

> Documentation :- https://twitteroauth.com/

### Bootstrap CSS library
> Link :-  https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css 

### Bootstrap JavaScript library
> Link :-  https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js

### jQuery JavaScript library
> Link :- https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js

### Autocomplete-ui
>Follwing both libraries used for autocomplete of textbox

> CSS :- https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css

> JavaScript :- https://code.jquery.com/ui/1.12.1/jquery-ui.js

> Documentation :- http://api.jqueryui.com/autocomplete/

### FPDF Library
> FPDF Library is used for pdf generation of user tweets

> Link :- http://www.fpdf.org/?lang=en

> Documentation :- http://www.fpdf.org/en/doc/index.php

### makeLinks.php
> Following Blog's code is used for make Links in tweets

> Link :- http://krasimirtsonev.com/blog/article/php--find-links-in-a-string-and-replace-them-with-actual-html-link-tags


## Steps to use this repo code

1. Download Source Code

2. Make App in Twitter Using this link https://apps.twitter.com/

3. Replace your App's CONSUMER KEY,CONSUMER SECRET KEY and CALLBACK URL with XXXXX in constant.php
> You can find Those details in your App's Details Section
  ```
  <?php
  /* This file contains constant values related to your app */
  define('CONSUMER_KEY', 'XXXXX'); // replace your app consumer key with XXXXX
  define('CONSUMER_SECRET', 'XXXXX'); // replace your app consumer secret key with XXXXX
  define('OAUTH_CALLBACK', 'XXXXX'); // replace your app callback URL with XXXXX
  ?>
  ```

