<?php
/* This file is created using following blog
	link:-http://krasimirtsonev.com/blog/article/php--find-links-in-a-string-and-replace-them-with-actual-html-link-tags
*/
/* This file contains one function makeLinks() */
/*This function is used to find links in tweet and place it in <a> tag */
function makeLinks($str) {
  $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
  $urls = array();
  $urlsToReplace = array();
  if (preg_match_all($reg_exUrl, $str, $urls)) {
    $numOfMatches = count($urls[0]);
    $numOfUrlsToReplace = 0;
    for ($i = 0; $i<$numOfMatches; $i++) {
      $alreadyAdded = false;
      $numOfUrlsToReplace = count($urlsToReplace);
      for ($j = 0; $j<$numOfUrlsToReplace; $j++) {
        if ($urlsToReplace[$j] == $urls[0][$i]) {
          $alreadyAdded = true;
        }
      }
      if (!$alreadyAdded) {
        array_push($urlsToReplace, $urls[0][$i]);
      }
    }
    $numOfUrlsToReplace = count($urlsToReplace);
    for ($i = 0; $i<$numOfUrlsToReplace; $i++) {
      $str = str_replace($urlsToReplace[$i], "<a target='_blank' href=\"".$urlsToReplace[$i]."\">".$urlsToReplace[$i]."</a> ", $str);
    }
    return $str;
  } else {
    return $str;
  }
}
