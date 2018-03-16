<?php

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

if($options = get_option( 'swptc_settings' )){

  $transient = get_transient( 'swptc_latest_tweets' );
  if ( 1 ){
    $connection = new TwitterOAuth($option['consumer_key'], $option['consumer_secret'], $option['access_token'], $option['access_token_secret']);
    $content = $connection->get("account/verify_credentials");
    $twitter_handles = explode(',', preg_replace('/\s+/', '', $option['twitter_handles']));
    $errors = array();
    $transient = array();
    foreach ($twitter_handles as $key => $handle) {
      $statuses = $connection->get("statuses/user_timeline", ["screen_name" => $handle, "count" => $option['num_tweets'], "exclude_replies" => false]);
      if(isset($statuses->errors)){
        $errors[] = $statuses->errors[0]->message;
      } else {
        if (isset($statuses[0])) {
          $transient[$handle] = $statuses;
        } else {
          $errors[] = 'No tweets found for ' . $handle;
        }
      }
    }
    if (count($errors) == 0) {
      if(set_transient( 'swptc_latest_tweets', $transient, $option['refresh_rate'] * HOUR_IN_SECONDS )) {
        echo 'Transient set.';
      } else {
        echo 'Transient not set.';
      }
    } else {
      echo 'Errors: ' . json_encode($errors);
    }
  } else {
    echo 'Transient already set.';
  }
} else {
  echo 'Please add settings.';
}
die();
