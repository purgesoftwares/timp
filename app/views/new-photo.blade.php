@extends('layouts/layout')

@section('content')
	@if(Session::has('message'))
		<div class="alert alert-dismissable">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		  {{ Session::get('message')}}
		</div>
	@endif

	@if (!empty($data))
	    <div class="media">
	      <a class="pull-left" href="#">
	        <img class="media-object" src="{{ $imageUrl }}" alt="Profile image">
	      </a>
	      <div class="media-body">
	        <h1 class="media-heading">Tribute to Indian Martyrs at Phatankot :: {{{ $name }}} </h1>
	      </div>
	    </div>
	    <hr>
	@else
		<div class="jumbotron">
		    <h1>Facebook login example</h1>
		    <p>Created by <a href="http://twitter.com/msurguy" target="_blank">Maks</a></p>
		    <p class="text-center">
		      <a class="btn btn-lg btn-primary" href="{{url('login/fb')}}"><i class="icon-facebook"></i> Login with Facebook</a>
		    </p>
		</div>
	@endif

@stop
