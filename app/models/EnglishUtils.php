<?php

class EnglishUtils {
	
	public static $twitterWords = array("rt");
	public static $articles = array("the","a","an");
	public static $prepositions = array(
			"aboard", "about", "above", "across", "after", "against", "along", "amid", "among",
			"anti", "around", "as", "at", "before", "behind", "below", "beneath", "beside", "besides",
			"between", "beyond", "but", "by", "concerning", "considering", "despite", "down", "during",
			"except", "excepting", "excluding", "following", "for", "from", "in", "inside", "into", "like",
			"minus", "near", "of", "off", "on", "onto", "opposite", "outside", "over", "past", "per", "plus",
			"regarding", "round", "save", "since", "than", "through", "to", "toward", "towards", "under", "underneath",
			"unlike", "until", "up", "upon", "versus", "via", "with", "within", "without");
	public static $conjunctions = array(
			"after","although","and", "as",
			"because", "before", "both", "but",
			"either", "even",
			"for",
			"how", "however",
			"if",
			"neither","nor","now",
			"once", "only", "or",
			"provided",
			"rather", "than",
			"since", "so",
			"than", "that", "though", "till",
			"unless", "until",
			"when", "whenever", "where", "whereas", "wherever", "whether", "while",
			"yet"
	);
	public static $pronouns = array(
			"all", "another", "any", "anybody", "anyone", "anything",
			"both",
			"each", "either", "everybody", "everyone", "everything",
			"few",
			"he", "her", "hers", "herself", "him", "himself", "his",
			"i", "it", "its", "itself",
			"many", "me", "mine", "more", "most", "much", "my", "myself",
			"neither", "nobody", "none", "nothing",
			"one","other", "others", "our", "ours", "ourselves",
			"several", "she", "some", "somebody","someone","something",
			"that", "their", "theirs", "them", "themselves", "these", "they", "this", "those",
			"us",
			"we", "what", "whatever", "which", "whichever", "who", "whoever", "whom", "whomever", "whose",
			"you", "your", "yours", "yourself", "yourselves"
	);
	public static $stateOfBeingVerbs = array(
			"is", "am", "are",
			"was", "were",
			"be", "being" ,"been"
	);
	
	public static function commonWordTypes(){
		$skipWords = array_merge(
				EnglishUtils::$articles,
				EnglishUtils::$conjunctions
		);
		$skipWords = array_merge(
				$skipWords,
				EnglishUtils::$stateOfBeingVerbs);
		$skipWords = array_merge(
				$skipWords,
				EnglishUtils::$pronouns);
		$skipWords = array_merge(
				$skipWords,
				EnglishUtils::$prepositions
		);
		$skipWords = array_merge(
				$skipWords,
				EnglishUtils::$twitterWords
		);
		return $skipWords;
	}
	
	public static function getWordArray($text){
		$text = strtolower($text);
			
		// Strip @s
		$text = preg_replace("/@[A-Za-z0-9_]{1,15}/",'',$text);
			
		// Strip hashtags (perhaps make optional?)
		$text = preg_replace("/#[a-zA-Z0-9_]+/",'',$text);
			
		// Strip URLs
		$rexProtocol = '(https?://)?';
		$rexDomain   = '((?:[-a-zA-Z0-9]{1,63}\.)+[-a-zA-Z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
		$rexPort     = '(:[0-9]{1,5})?';
		$rexPath     = '(/[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]*?)?';
		$rexQuery    = '(\?[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
		$rexFragment = '(#[!$-/0-9:;=@_\':;!a-zA-Z\x7f-\xff]+?)?';
			
		$text = preg_replace("&\\b$rexProtocol$rexDomain$rexPort$rexPath$rexQuery$rexFragment(?=[?.!,;:\"]?(\s|$))&",
				'', $text);
			
		$words = array();
		preg_match_all("/[a-z][a-z0-9'\-_]*/", $text, $words);
		
		$wordArr = array();
		foreach($words[0] as $word){
			if($word != '' && !in_array($word,EnglishUtils::commonWordTypes())){
				array_push($wordArr,$word);
			}
		}
		
		return $wordArr;
	}
	
	public static function countWords($wordArray){
		$wordList = array();
		foreach($wordArray as $w){
			if(isset($wordList[$w])){
				$wordList[$w]++;
			} else {
				$wordList[$w] = 1;
			}
		}
		return $wordList;
	}
	
	public static function sortByWordLength($wordArray){
		$wordList = array();
		foreach($wordArray as $w){
			if(!isset($wordList[$w])){
				$wordList[$w] = strlen($w);
			}
		}
		arsort($wordList);
		return array_keys($wordList);
	}
}