@extends('template')

@section('body')

@include("stats-angular-inner")

@stop

@section('script')

<!-- Twitter Widgets -->
<script src="http://platform.twitter.com/widgets.js"></script>

<!-- Angular JS -->   
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0-beta.14/angular.min.js"></script>

<script>

//declare a module
var App = angular.module('VocabStatsApp', []);

App.controller('VocabStatsController', function($scope, $http) {
    $scope.processedTweets = 0;
	$scope.handle = "{{ $handle }}";
	$scope.totalTweets = {{ $totalTweets }};
	$scope.maxProcessedTweets = $scope.totalTweets;
	if($scope.totalTweets > 3200){
		$scope.maxProcessedTweets = 3200;
	}
	$scope.wordcount = {};
	$scope.working = true;
	$scope.hasError = false;
	$scope.complete = false;
	
	$scope.processWordCount = function(res){
		var wc = res.data;

		if(wc.error != null){
			$scope.hasError = true;
			$scope.working = false;
			$scope.complete = false;
			$scope.errorMessage = wc.error;
			return;
		}
		
        $scope.text = "";
        
		$scope.processedTweets += wc.tweetsProcessed;
        
		var obj = wc.wordlist;
		var count = 0;
		
        for(var key in obj){
            var attrName = key;
            var attrValue = obj[key];
            if($scope.wordcount.hasOwnProperty(key)){
            	$scope.wordcount[key] += attrValue;
            }	else {
            	$scope.wordcount[key] = attrValue;
            }
        }

        if(res.data.tweetsProcessed > 0){
        	$http.get('/api/{{ $handle }}/wordcount?maxId=' + (wc.lastId - 1)).then($scope.processWordCount)
        } else {
        	$scope.topWords = [];
        	$scope.wordsByLength = [];
        	var wordsSorted = [];
        	for(var key in $scope.wordcount){
            	var data = {};
            	data.word = key;
            	data.count = $scope.wordcount[key];
				wordsSorted.push(data); 
            }
            wordsSorted.sort(function(a,b){ return b.count - a.count; });
			for(var i = 0; i < wordsSorted.length; i++){
	            $scope.topWords[i] = wordsSorted[i].word;
			}
			
			wordsSorted.sort(function(a,b){ return b.word.length - a.word.length; });
			for(var i = 0; i < wordsSorted.length; i++){
	            $scope.wordsByLength[i] = wordsSorted[i].word;
			}

			var l = window.location.href;
			
			// Display the Twitter share button
			twttr.widgets.load();
			twttr.widgets.createShareButton(
					l.replace("/" + $scope.handle, ""),
                    angular.element(document.getElementById('tweetShare'))[0],
                    function(el) {}, {
                        count: 'none',
                        text: $scope.handle + "'s most tweeted word was \"" + $scope.topWords[0] + "\", what's yours?",
                        size: 'large'
                    }
                );

			$scope.working = false;
			$scope.complete = true;
        }
	}

	
	  $http.get('/api/{{ $handle }}/wordcount')
	       .then($scope.processWordCount);
	});

</script>

@stop