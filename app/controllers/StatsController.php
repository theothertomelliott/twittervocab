<?php

class StatsController extends BaseController {

	public function getUserInfo($handle){
		$tu = new TwitterUtils($handle);
		return $tu->getUserInfo();
	}
	
	public function getWordCount($handle){
		
		try {
		
		$wordArr = array();

		$maxId = -1;

		if(Input::has("maxId")){
			$maxId = Input::get("maxId");
		}

		$count = 0;

		$tu = new TwitterUtils($handle);
		$lastId = $tu->processTweetSegment(function ($tweet) use (&$wordArr, &$count) {
			$words = EnglishUtils::getWordArray($tweet['text']);
			$wordArr = array_merge($wordArr, $words);
			$count++;
		},$maxId);

		$wordList = EnglishUtils::countWords($wordArr);
				
		arsort($wordList);
				
		return array('tweetsProcessed' => $count, 'lastId' => $lastId, 'wordlist' => $wordList);
		
		} catch (TwitterUserNotFoundException $e){
			return array("error" => "TwitterUserNotFound");
		} catch (TwitterUnexpectedException $e){
			return array("error" => "TwitterUserNotFound");
		} catch (TwitterRateLimitException $e){
			return array("error" => "TwitterRateLimitException");
		} catch (Exception $e){
			return array("error" => "Exception");
		}
	}
	
	public function getCurrentUser(){
		$tu = new TwitterUtils();
		$r = $tu->getCurrentUser();
		return $r;
	}

	public function getWordStats($handle)
	{
		try {

			$tu = new TwitterUtils($handle);

			$wordArr = array();

			$calls = $tu->processTweets(function ($tweet) use (&$wordArr) {
				$words = EnglishUtils::getWordArray($tweet['text']);
				$wordArr = array_merge($wordArr, $words);
			});
					
				if($calls <= 0){
					return Redirect::to("/")->withErrors(array("User not found or no tweets"));
				}

				$wordList = EnglishUtils::countWords($wordArr);

				arsort($wordList);

				$wordsByLength = EnglishUtils::sortByWordLength($wordArr);

				return View::make("wordstats", array( "handle" => $handle, "totalTweets" => $calls, "wordList" => $wordList, "wordsByLength" => $wordsByLength ));

		} catch (TwitterUserNotFoundException $e){
			return Redirect::to("/")->withErrors(array("User not found."));
		} catch (TwitterUnexpectedException $e){
			return Redirect::to("/")->withErrors(array("Unexpected exception while retrieving Tweets."));
		}

	}

	public function getChooseHandle(){
		return View::make("choosehandle");
	}
	
	public function getStatsAngular($handle){
		try {
			$tu = new TwitterUtils($handle);
			$stats = $tu->getUserInfo();
			
			return View::make("stats-angular", array("handle" => $handle, "totalTweets" => $stats['statuses_count']));
		} catch (TwitterUserNotFoundException $e){
			return Redirect::to("/")->withErrors(array("User not found."));
		} catch (TwitterUnexpectedException $e){
			return Redirect::to("/")->withErrors(array("Unexpected exception while retrieving Tweets."));
		}
	}

}