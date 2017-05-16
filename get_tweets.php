<?php

require "vendor/autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

if($options = get_option( 'swptc_settings' )){

  $transient = get_transient( 'swptc_latest_tweets' );
  if ( 1 ){
    $connection = new TwitterOAuth($option['consumer_key'], $option['consumer_secret'], $option['access_token'], $option['access_token_secret']);
    $content = $connection->get("account/verify_credentials");
    $statuses = $connection->get("statuses/user_timeline", ["screen_name" => $option['twitter_handle'], "count" => $option['num_tweets'], "exclude_replies" => true]);
    if(isset($statuses->errors)){
      echo $statuses->errors[0]->message;
    }else{
      if (isset($statuses[0])){
        if(set_transient( 'swptc_latest_tweets', $statuses, $option['refresh_rate'] * HOUR_IN_SECONDS ))
          echo 'Transient set.';
        else
          echo 'Transient not set.';
      }else{
        echo 'No statueses found.';
      }
    }
  }else{
    echo 'Transient already set.';
  }
}else{
  echo 'Please add settings.';
}
die();
