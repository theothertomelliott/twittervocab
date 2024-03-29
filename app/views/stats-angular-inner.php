<div id="stats-app" ng-app="VocabStatsApp" ng-controller="VocabStatsController" ng-cloak>

<div id="progress" ng-show="working" >
<h3>Processing {{ handle }}'s tweets...</h3>

<div class="progress">
  <div class="progress-bar" role="progressbar" aria-valuenow="{{ (processedTweets/maxProcessedTweets)*100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ (processedTweets/maxProcessedTweets)*100 }}%;">
		{{ (processedTweets/maxProcessedTweets)*100 | number:0 }}%
  </div>
</div>
{{ processedTweets }} of {{ maxProcessedTweets }} tweets ({{ totalTweets }} total)	<sup><a href="#" data-toggle="modal" data-target="#infoApiLimit">(?)</a></sup>
</div>

<div id="vocab" ng-show="hasError">
	<h1><i class="fa fa-lg fa-warning"></i>&nbsp;Error</h1>
	<p>Your request failed with the following error: {{ errorMessage }}. Please try again later:</p>
	<form method="post" action="/handle">
		<p>
			<input type="text" name="handle" value="{{ handle }}"/>
			<input type="submit" value="Go" class="btn btn-primary btn-lg" role="button"/>
		</p>
	</form>
</div>

<div id="vocab" ng-show="complete" >

<h1><i class="fa fa-lg fa-twitter"></i>&nbsp;<a href="http://www.twitter.com/{{ handle}}">{{ handle }}</a>'s Twitter Vocab</h1>

<p>{{ handle }} used {{ wordsByLength.length }} words in their last {{ processedTweets }}
	<sup><a href="#" data-toggle="modal"
   data-target="#infoApiLimit">(?)</a></sup>
    tweets.</p>

<h3>{{ handle }}'s favourite words</h3>
<ol>
	<li>{{ topWords[0] }} (used {{ wordcount[topWords[0]] }} times)</li>
	<li>{{ topWords[1] }} (used {{ wordcount[topWords[1]] }} times)</li>
	<li>{{ topWords[2] }} (used {{ wordcount[topWords[2]] }} times)</li>
</ol>

<h3>{{ handle }}'s longest words</h3>

<ol>
	<li>{{ wordsByLength[0] }} ({{ wordsByLength[0].length }} letters)</li>
	<li>{{ wordsByLength[1] }} ({{ wordsByLength[1].length }} letters)</li>
	<li>{{ wordsByLength[2] }} ({{ wordsByLength[2].length }} letters)</li>
</ol>

<br/>

<h2>Share your Results</h2>

<a id="tweetShare"></a>

<br/>
<br/>

<h3><a href="/">&lt;&lt; Start Again</a></h3>

<div class="modal fade" id="infoApiLimit" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h2 class="modal-title" id="myModalLabel">Why aren't you using all my Tweets?</h2>
            </div>
            <div class="modal-body">
                <p>The Twitter API limits how far back you can read a user's timeline. At most, you can retrieve about 3200 tweets.</p>
                <p>Not ideal, I'll admit, but it's enough to give an idea of your language at present.</p>
            </div>
            <div class="modal-footer">
	            <a href="#" data-dismiss="modal" class="btn btn-default">OK</a>
        	</div>
    </div>
  </div>
</div>

</div>

</div>
