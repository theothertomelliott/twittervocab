@extends('template')

@section('body')
<div class="text-center">

<h1><i class="fa fa-lg fa-twitter"></i> Twitter Vocabulary</h1>

<p>Enter your Twitter handle below and press Go to find out about your use of words on Twitter</p>

@if($errors != null && count($errors) > 0)
<div class="alert alert-error">  
  <a class="close" data-dismiss="alert">x</a>  
  <strong>Warning!</strong> {{ $errors->first() }}
</div>
@endif

<form method="post" action="/handle">
<p>
<input type="text" name="handle"/>
<input type="submit" value="Go" class="btn btn-primary btn-lg" role="button"/>
</p>
</form>

</div>

@stop