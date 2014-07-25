<?php

class TwitterUserNotFoundException extends Exception {}
class TwitterUnexpectedException extends Exception {}

class TwitterUtils {
	
	private $handle;
	
	private $APIExchange;
	
	public function __construct($handle = null){
		$this->handle = $handle;
		
		$settings = array(
				'oauth_access_token' => "2095001-EVbeBb3WtbL97tSZTAkZcuXRe8JayrsOsj1leGHwA",
				'oauth_access_token_secret' => "fMt32fm4jvGuTrjZHQMQoCUgVdSc1XoSOdf36TqOY",
				'consumer_key' => "gvYIGsz83SYmINtXNNeig",
				'consumer_secret' => "evMI33QnkHIronR6vgccHqIubcXzI26qd0ZAn5Y3zQ"
		);
		
		$this->APIExchange = new TwitterAPIExchange($settings);
	}
	
	public function getCurrentUser(){
		$url = 'https://api.twitter.com/1.1/account/verify_credentials.json';
		$result = $this->APIExchange->buildOauth($url, 'GET')
		->performRequest();
		
		return $result;
	}
	
	public function getUserInfo(){
		$url = 'https://api.twitter.com/1.1/users/show.json';
		$getfield = '?screen_name=' . $this->handle;
		$requestMethod = 'GET';
		
		$result = $this->APIExchange->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
		
		$output = json_decode($result, true);
		
		if(isset($output['errors'])){
			if($output['errors'][0]['code'] == 34){
				throw new TwitterUserNotFoundException();
			} else {
				throw new TwitterUnexpectedException();
			}
		}
		
		return $output;
	}
	
	private function getTweetsHelper($maxTweets,$maxId = -1){
	
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name=' . $this->handle . '&count=' . $maxTweets;
		if($maxId != -1){
			$getfield .= "&max_id=" . $maxId;
		}
		$requestMethod = 'GET';
	
		$result = $this->APIExchange->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
	
		$output = json_decode($result, true);
		
		if(isset($output['errors'])){
			if($output['errors'][0]['code'] == 34){
				throw new TwitterUserNotFoundException();
			} else {
				throw new TwitterUnexpectedException();
			}
		}
		
		return $output;
	}
	
	public function processTweetSegment($processor,$maxId = -1,$maxTweets = 200){
		$lastId = 0;
		
		$tweet_segment = $this->getTweetsHelper($maxTweets,$maxId);
		foreach($tweet_segment as $tweet){
			$lastId = $tweet['id'];
			$processor($tweet);
		}
		
		return $lastId;
	}
	
	public function processTweets($processor){
	
		$maxCount = 200;
		$count = 0;
		
		for(	$tweet_segment = $this->getTweetsHelper($maxCount,-1); 
				count($tweet_segment) > 0;
				$tweet_segment = $this->getTweetsHelper($maxCount,end($tweet_segment)['id']-1)
			){
		
			foreach($tweet_segment as $tweet){
				$count++;
				$processor($tweet);
			}
			
		}
		
		return $count;
		
	}
	
}