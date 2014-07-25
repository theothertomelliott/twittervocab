@extends('template')

@section('body')

<h1>{{ $handle }}'s Twitter Vocab</h1>

<p>{{ $handle }} used {{ count(array_keys($wordList)) }} words in their last {{ $totalTweets }}
	<sup><a href="#" data-toggle="modal"
   data-target="#infoApiLimit">(?)</a></sup>
    tweets.</p>

<h3>{{ $handle }}'s favourite words</h3>
<ol>
	<li>{{ array_keys($wordList)[0] }} (used {{ $wordList[array_keys($wordList)[0]] }} times)</li>
	<li>{{ array_keys($wordList)[1] }} (used {{ $wordList[array_keys($wordList)[1]] }} times)</li>
	<li>{{ array_keys($wordList)[2] }} (used {{ $wordList[array_keys($wordList)[2]] }} times)</li>
</ol>

<h3>{{ $handle }}'s longest words</h3>

<ol>
	<li>{{ $wordsByLength[0] }} ({{ strlen($wordsByLength[0]) }} letters)</li>
	<li>{{ $wordsByLength[1] }} ({{ strlen($wordsByLength[1]) }} letters)</li>
	<li>{{ $wordsByLength[2] }} ({{ strlen($wordsByLength[2]) }} letters)</li>
</ol>

<hr/>

<h2>Share your Results</h2>

<a href="https://twitter.com/share" class="twitter-share-button" data-text="{{ $handle }}'s most tweeted word was &quot;{{ array_keys($wordList)[0] }}&quot;, what's yours?" data-size="large">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>

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

@stop

