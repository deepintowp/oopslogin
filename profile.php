<?php 

require_once('core/init.php');


if(!$username = Input::get('username')){
	Redirect::To('index.php');
}else{
	$user = new User(mysql_real_escape_string(Input::get('username')));
	if(!$user->exists()){
		Redirect::To(404);
	}else{
		
		$data = $user->data();
	}
	
	
	
	
}
?>

<h3><b>Name: </b><?php echo $data->name; ?></h3>
<h3><b>Username: </b><?php echo $data->username; ?></h3>