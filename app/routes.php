<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
    $data = array();
    if (Auth::check()) {
        $data = Auth::user();
    }
    return View::make('user', array('data'=>$data));
});

Route::get('login/fb', function() {
    $facebook = new Facebook(Config::get('facebook'));
    $params = array(
        'redirect_uri' => url('/login/fb/callback'),
        'scope' => 'email,publish_actions',
    );
    return Redirect::to($facebook->getLoginUrl($params));
});

Route::get('privacy-policy', function() {
    
    return View::make('provacy', array());
});
Route::get('login/fb1/callback', function() {
    $code = Input::get('code');
    if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

    /*if(!isset($_SESSION['token']) || $_SESSION['token']==null){
        echo $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=1036766789679467&redirect_uri=" . url('/login/fb/callback')
       . "&client_secret=3986e9f90837ef8f5760911b6dd61ff3&code=" . $code; 


        $ch = curl_init();
 
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $token_url);
     
        $data = curl_exec($ch);
        curl_close($ch);

        $params = null;
        parse_str($data, $params);
        print_r($params);   
        print_r($data); die;
        $_SESSION['token'] = $params['access_token'];
    }*/
    
    $facebook = new Facebook(Config::get('facebook'));
    $uid = $facebook->getUser();
     
    if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');
     
    $me = $facebook->api('/me?fields=id,name,picture.width(800).height(800)');
    //?fields=id,name,picture.width(800).height(800)
    // print_r($me); die;
	/*$profile = Profile::whereUid($uid)->first();
    // print_r($profile); die;
    if (empty($profile)) {

    	$user = new User;
    	$user->name = $me['first_name'].' '.$me['last_name'];
    	$user->email = $me['email'];
    	$user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';

        $user->save();

        $profile = new Profile();
        $profile->uid = $uid;
    	$profile->username = $me['id'];
    	$profile = $user->profiles()->save($profile);
    }*/
     
   /* $profile->access_token = $facebook->getAccessToken();
    $profile->save();*/

  /*  $user = $profile->user;
 
    Auth::login($user);*/
     
    $img = file_get_contents($me["picture"]["data"]["url"]);
    // $img = file_get_contents('https://graph.facebook.com/'.$uid.'/picture?type=large');
    $file = public_path().'/avatar/'.$uid.'.jpg';
    file_put_contents($file, $img);

    /*  modify image */


    // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefromjpeg(public_path().'/avatar/incredible-india.jpg');

        // First we create our stamp image manually from GD
        //$stamp = imagecreatetruecolor(200, 200);
        //imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF);
        //imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF);
        $im = imagecreatefromjpeg($file);
        //imagestring($stamp, 5, 20, 20, 'libGD', 0x0000FF);
        //imagestring($stamp, 3, 20, 40, '(c) 2007-9', 0x0000FF);

        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 0;
        $marge_bottom = 0;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 25);

        // Save the image to file and free memory
        imagepng($im, public_path().'/avatar/new-profile-'.$uid.'.jpg');
        imagedestroy($im);

    /* End modify image */
    // 'appId' => "1036766789679467",
    //'secret' => "3986e9f90837ef8f5760911b6dd61ff3"
   

        // $image['access_token']  = $_SESSION['token'];
        // $image['message']       = '';
		$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
                                            Create It Now, Click here http://tribute.purgesoft.com/login/fb
                                            Show country love on this #RepublicDay' ;
                //$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
                //$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
											//Create It Now, Click here http://tribute.purgesoft.com/login/fb' ;
                //$image['image']         = '@'.realpath(public_path().'/avatar/new-profile-'.$uid.'.jpg');
                $image['image']         = new CurlFile(public_path().'/avatar/new-profile-'.$uid.'.jpg', 'image/jpg');;
                $facebook->setFileUploadSupport(true);
                $img = $facebook->api('/me/photos', 'POST', $image);

            // print_r($img); die;


    return Redirect::to('https://www.facebook.com/photo.php?fbid='.$img["id"].'&type=3&makeprofile=1&pp_source=photo_view');
   /* return Redirect::to('/')->with('data', ["name"=>$me['name'],
     'imageUrl'=> asset('avatar/new-profile-'.$uid.'.jpg')]);
*/
});


Route::get('login/fb/callback', function() {
    $code = Input::get('code');
    if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

    /*if(!isset($_SESSION['token']) || $_SESSION['token']==null){
        echo $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=1036766789679467&redirect_uri=" . url('/login/fb/callback')
       . "&client_secret=3986e9f90837ef8f5760911b6dd61ff3&code=" . $code; 


        $ch = curl_init();
 
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $token_url);
     
        $data = curl_exec($ch);
        curl_close($ch);

        $params = null;
        parse_str($data, $params);
        print_r($params);   
        print_r($data); die;
        $_SESSION['token'] = $params['access_token'];
    }*/
    
    $facebook = new Facebook(Config::get('facebook'));
    $uid = $facebook->getUser();
     
    if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');
     
    $me = $facebook->api('/me?fields=id,name,email,picture.width(800).height(800)');
	
	
    //?fields=id,name,picture.width(800).height(800)
    // print_r($me); die;
	/*$profile = Profile::whereUid($uid)->first();
    // print_r($profile); die;
    if (empty($profile)) {

    	$user = new User;
    	$user->name = $me['first_name'].' '.$me['last_name'];
    	$user->email = $me['email'];
    	$user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';

        $user->save();

        $profile = new Profile();
        $profile->uid = $uid;
    	$profile->username = $me['id'];
    	$profile = $user->profiles()->save($profile);
    }*/
     
   /* $profile->access_token = $facebook->getAccessToken();
    $profile->save();*/

  /*  $user = $profile->user;
 
    Auth::login($user);*/
     
    $img = file_get_contents($me["picture"]["data"]["url"]);
    // $img = file_get_contents('https://graph.facebook.com/'.$uid.'/picture?type=large');
    $file = public_path().'/avatar/'.$uid.'.jpg';
    file_put_contents($file, $img);
	
	list($width, $height) = getimagesize($file);
	
	$imagetoset = "incredible-india.jpg";
	//echo $width; die;
	if($width<481){
		
		if($width<201){
			
			//$img = file_get_contents($me["picture"]["data"]["url"]);
			$img = file_get_contents('https://graph.facebook.com/'.$uid.'/picture?type=large');
			$file = public_path().'/avatar/'.$uid.'.jpg';
			file_put_contents($file, $img);
			$imagetoset = "incredible-india200.jpg";
		}else{
			$me = $facebook->api('/me?fields=id,name,email,picture.width(400).height(400)');
		
			$img = file_get_contents($me["picture"]["data"]["url"]);
			// $img = file_get_contents('https://graph.facebook.com/'.$uid.'/picture?type=large');
			$file = public_path().'/avatar/'.$uid.'.jpg';
			file_put_contents($file, $img);
			
			//list($width, $height) = getimagesize($file);
			$imagetoset = "incredible-india480.jpg";
		}
		
		
		
		
	}
	
		$user = new User;
    	$user->first_name = $me['name'];
    	$user->fb_id = $me['id'];
    	$user->email = $me['email'];
    	$user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';

        $user->save();

    /*  modify image */


    // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefromjpeg(public_path().'/avatar/'.$imagetoset);

        // First we create our stamp image manually from GD
        //$stamp = imagecreatetruecolor(200, 200);
        //imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF);
        //imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF);
        $im = imagecreatefromjpeg($file);
        //imagestring($stamp, 5, 20, 20, 'libGD', 0x0000FF);
        //imagestring($stamp, 3, 20, 40, '(c) 2007-9', 0x0000FF);

        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 0;
        $marge_bottom = 0;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 25);

        // Save the image to file and free memory
        imagepng($im, public_path().'/avatar/new-profile-'.$uid.'.jpg');
        imagedestroy($im);

    /* End modify image */
    // 'appId' => "1036766789679467",
    //'secret' => "3986e9f90837ef8f5760911b6dd61ff3"
   

        // $image['access_token']  = $_SESSION['token'];
        // $image['message']       = '';
		$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
                                            Create It Now, Click here http://tribute.purgesoft.com/login/fb
                                            Show country love on this #RepublicDay' ;
                //$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
                //$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
											//Create It Now, Click here http://tribute.purgesoft.com/login/fb' ;
                //$image['image']         = '@'.realpath(public_path().'/avatar/new-profile-'.$uid.'.jpg');
                $image['image']         = new CurlFile(public_path().'/avatar/new-profile-'.$uid.'.jpg', 'image/jpg');;
                $facebook->setFileUploadSupport(true);
                $img = $facebook->api('/me/photos', 'POST', $image);

            // print_r($img); die;


    return Redirect::to('https://www.facebook.com/photo.php?fbid='.$img["id"].'&type=3&makeprofile=1&pp_source=photo_view');
   /* return Redirect::to('/')->with('data', ["name"=>$me['name'],
     'imageUrl'=> asset('avatar/new-profile-'.$uid.'.jpg')]);
*/
});


Route::get('create/fb', function() {
    $facebook = new Facebook(Config::get('facebook'));
    $params = array(
        'redirect_uri' => url('/create/fb/callback'),
        'scope' => 'email,publish_actions',
    );
    return Redirect::to($facebook->getLoginUrl($params));
});


Route::get('create/fb/callback', function() {
    $code = Input::get('code');
    if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

    /*if(!isset($_SESSION['token']) || $_SESSION['token']==null){
        echo $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=1036766789679467&redirect_uri=" . url('/login/fb/callback')
       . "&client_secret=3986e9f90837ef8f5760911b6dd61ff3&code=" . $code; 


        $ch = curl_init();
 
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $token_url);
     
        $data = curl_exec($ch);
        curl_close($ch);

        $params = null;
        parse_str($data, $params);
        print_r($params);   
        print_r($data); die;
        $_SESSION['token'] = $params['access_token'];
    }*/
    
    $facebook = new Facebook(Config::get('facebook'));
    $uid = $facebook->getUser();
     
    if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');
     
    $me = $facebook->api('/me?fields=id,name,email,picture.width(800).height(800)');
	
	
    //?fields=id,name,picture.width(800).height(800)
    // print_r($me); die;
	/*$profile = Profile::whereUid($uid)->first();
    // print_r($profile); die;
    if (empty($profile)) {

    	$user = new User;
    	$user->name = $me['first_name'].' '.$me['last_name'];
    	$user->email = $me['email'];
    	$user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';

        $user->save();

        $profile = new Profile();
        $profile->uid = $uid;
    	$profile->username = $me['id'];
    	$profile = $user->profiles()->save($profile);
    }*/
     
   /* $profile->access_token = $facebook->getAccessToken();
    $profile->save();*/

  /*  $user = $profile->user;
 
    Auth::login($user);*/
     
    $img = file_get_contents($me["picture"]["data"]["url"]);
    // $img = file_get_contents('https://graph.facebook.com/'.$uid.'/picture?type=large');
    $file = public_path().'/avatar/'.$uid.'.jpg';
    file_put_contents($file, $img);
	
	list($width, $height) = getimagesize($file);
	
	$imagetoset = "incredible-india.jpg";
	//echo $width; die;
	if($width<481){
		
		if($width<201){
			
			//$img = file_get_contents($me["picture"]["data"]["url"]);
			$img = file_get_contents('https://graph.facebook.com/'.$uid.'/picture?type=large');
			$file = public_path().'/avatar/'.$uid.'.jpg';
			file_put_contents($file, $img);
			$imagetoset = "incredible-india200.jpg";
		}else{
			$me = $facebook->api('/me?fields=id,name,email,picture.width(400).height(400)');
		
			$img = file_get_contents($me["picture"]["data"]["url"]);
			// $img = file_get_contents('https://graph.facebook.com/'.$uid.'/picture?type=large');
			$file = public_path().'/avatar/'.$uid.'.jpg';
			file_put_contents($file, $img);
			
			//list($width, $height) = getimagesize($file);
			$imagetoset = "incredible-india480.jpg";
		}
		
		
		
		
	}
	
		$user = new User;
    	$user->first_name = $me['name'];
    	$user->fb_id = $me['id'];
    	$user->email = $me['email'];
    	$user->photo = 'https://graph.facebook.com/'.$me['id'].'/picture?type=large';

        $user->save();

    /*  modify image */


    // Load the stamp and the photo to apply the watermark to
        $stamp = imagecreatefromjpeg(public_path().'/avatar/'.$imagetoset);

        // First we create our stamp image manually from GD
        //$stamp = imagecreatetruecolor(200, 200);
        //imagefilledrectangle($stamp, 0, 0, 99, 69, 0x0000FF);
        //imagefilledrectangle($stamp, 9, 9, 90, 60, 0xFFFFFF);
        $im = imagecreatefromjpeg($file);
        //imagestring($stamp, 5, 20, 20, 'libGD', 0x0000FF);
        //imagestring($stamp, 3, 20, 40, '(c) 2007-9', 0x0000FF);

        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = 0;
        $marge_bottom = 0;
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopymerge($im, $stamp, imagesx($im) - $sx - $marge_right, imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), 25);

        // Save the image to file and free memory
        imagepng($im, public_path().'/avatar/new-profile-'.$uid.'.jpg');
        imagedestroy($im);

    /* End modify image */
    // 'appId' => "1036766789679467",
    //'secret' => "3986e9f90837ef8f5760911b6dd61ff3"
   

        // $image['access_token']  = $_SESSION['token'];
        // $image['message']       = '';
		$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
                                            Create It Now, Click here http://tribute.purgesoft.com/login/fb
                                            Show country love on this #RepublicDay' ;
                //$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
                //$image['message']       = 'Change your profile picture for a Tribute to Indian Martyrs - Phathankot :: '.$me['name'].'
											//Create It Now, Click here http://tribute.purgesoft.com/login/fb' ;
                //$image['image']         = '@'.realpath(public_path().'/avatar/new-profile-'.$uid.'.jpg');
                $image['image']         = new CurlFile(public_path().'/avatar/new-profile-'.$uid.'.jpg', 'image/jpg');;
                $facebook->setFileUploadSupport(true);
                $img = $facebook->api('/me/photos', 'POST', $image);

            // print_r($img); die;


    return Redirect::to('https://www.facebook.com/photo.php?fbid='.$img["id"].'&type=3&makeprofile=1&pp_source=photo_view');
   /* return Redirect::to('/')->with('data', ["name"=>$me['name'],
     'imageUrl'=> asset('avatar/new-profile-'.$uid.'.jpg')]);
*/
});

Route::get('logout', function() {
    Auth::logout();
    return Redirect::to('/');
});
