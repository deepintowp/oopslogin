<?php
require_once('core/init.php');
$User = new User();
if(!$User->isLoggedin()){
	Redirect::To('index.php');
}
if(Input::exists() && Token::check(Input::get('token'))  ){
	$validate = new Validation();
	$validation = $validate->check($_POST, array(
					'password_current'=>array(
						'required'=>true,
						'min'=>6,
						'max'=>20,
						
					),
					'password_new'=>array(
						'required'=>true,
						'min'=>6,
						'max'=>20,
						
					),
					'password_new_again'=>array(
						'required'=>true,
						'min'=>6,
						'max'=>20,
						'matches'=> 'password_new'
						
					),
					
					
				));
	if($validation->passed()){
		if( Hash::make( Input::get('password_current'), $User->data()->salt)  !== $User->data()->password  ){
			echo 'current pass is wrong';
		}else{
			$salt = Hash::salt(32);
			$User->update(array(
						'password' => Hash::make( Input::get('password_new'), $salt),
						'salt' => $salt,
						
						
					));
			Session::flash('home', 'Password Changed Successfully.');
			Redirect::To('index.php');
		}
		
	}else{
		if($validation->error()){
			foreach($validation->error() as $error ){
				echo '<h6 style="color:red">'.$error.'</h6><br />';
			}
		}
	}			
	
}


?>

<form  method="post" action="">
	<div class="field">
		<label for="password_current">Current Password</label>
		<input name="password_current" id="password_current" type="text" />
	</div>
	
	<div class="field">
		<label for="password_new">New Password</label>
		<input name="password_new" id="password_new" type="text" />
	</div>
	
	<div class="field">
		<label for="password_new_again">Password Again</label>
		<input name="password_new_again" id="password_new_again" type="text" />
	</div>
	
	<input type="submit" value="Change Password" />
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	
</form>