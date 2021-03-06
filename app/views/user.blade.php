@extends('layouts/layout')

@section('content')
	@if(Session::has('message'))
		<div class="alert alert-dismissable">
		  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		  {{ Session::get('message')}}
		</div>
	@endif



	@if(Session::has('data'))
	    <div class="media">
	      <a class="pull-left" href="#">
	        <img class="media-object" width="200px" src="{{ Session::get('data')['imageUrl'] }}" alt="Profile image">
	      </a>
	      <div class="media-body">
	        <h1 class="media-heading">
	        Tribute to Indian Martyrs - Phatankot :: {{{ Session::get('data')['name'] }}} </h1>
	        <p>If you support India and Want to stop terrorism. 
	        And want to show your tribute to indian Martyrs And a selute to soldiers, <br/>
	        Update your profile picture with profile picture overlaid by indian flag(Tiranga).</p>
	      </div>

	      <div class="fb-like" data-href="http://localhost:8000/"
	       data-layout="button" data-action="like" 
	       data-show-faces="true" data-share="true"></div>

	    </div>
	    <hr>
	    <a href="{{url('logout')}}">Logout</a>
	@else
	<div class="media">
	      <a class="pull-left" href="#">
	        <img class="media-object" width="200px" src="avatar/new-profile-977077305685901.jpg" alt="Profile image">
	      </a>
	      <div class="media-body">
	        <h1 class="media-heading">
	        Tribute to Indian Martyrs - Phatankot :: {{{ Session::get('data')['name'] }}} </h1>
	        <p>If you support India and Want to stop terrorism. 
	        And want to show your tribute to indian Martyrs And a selute to soldiers, <br/>
	        Update your profile picture with profile picture overlaid by indian flag(Tiranga).</p>
	      </div>

	      <div class="fb-like" data-href="http://tribute.purgesoft.com/"
	       data-layout="button" data-action="like" 
	       data-show-faces="true" data-share="true"></div>

	    </div>
	    <hr>
		
		<div class="jumbotron">
		    <h1>Create and Post Your profile picture with indian flag</h1>
		    
		    <p class="text-center">
		      <a class="btn btn-lg btn-primary" href="{{url('login/fb')}}"><i class="icon-facebook"></i> Create and Post</a>
		    </p>
		</div>
	@endif

	<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId=1036766789679467";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

@stop

