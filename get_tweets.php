<?php

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

if($options = get_option( 'swptc_settings' )){

  $transient = get_transient( 'swptc_latest_tweets' );
  if ( empty( $transient ) ){
    $connection = new TwitterOAuth($option['consumer_key'], $option['consumer_secret'], $option['access_token'], $option['access_token_secret']);
    $content = $connection->get("account/verify_credentials");
    $statuses = $connection->get("statuses/user_timeline", ["screen_name" => $option['twitter_handle'], "count" => $option['num_tweets']]);
    if (isset($statuses[0])){
      if(set_transient( 'swptc_latest_tweets', $statuses, $option['refresh_rate'] * HOUR_IN_SECONDS ))
        echo 'transient set';
      else
        echo 'transient not set';
    }else{
      echo 'no statueses found';
    }
  }else{
    echo 'transient already set';
  }
}else{
  echo 'Please add settings';
}
die();
