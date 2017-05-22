<?php
require_once('core/init.php');
$User = new User();
if($User->isLoggedin()){
	Redirect::To('index.php');
}
if(Input::exists() && Token::check(Input::get('token'))  ){
	$validate = new Validation();
	$validation = $validate->check($_POST, array(
					'username'=>array(
						'required'=>true,
						
					),
					'password'=>array(
						'required'=>true,
						
					),
					
				));
	if($validation->passed()){
		$User = new User();
		$remember = Input::get('remember') === 'on'? true : false;
		
		$login = $User->login(Input::get('username'), Input::get('password'), $remember);
		if($login){
			Redirect::To('index.php');
		}else{
			echo 'login faild.';
		}
	}else{
		if($validation->error()){
			foreach($validation->error() as $error ){
				echo '<h6 style="color:red">'.$error.'</h6><br />';
			}
		}
	}			
	
}
if(isset($_SESSION['user'])){
	echo 'you are logged in.';
}

?>
<form method="post" action="">
	<div class="field">
		<label for="username">Usernmae:</label>
		<input id="username" name="username" type="text" />
	</div>
	
	<div class="field">
		<label for="password">Password:</label>
		<input id="password" name="password" type="text" />
	</div>
	<div class="field">
		<label for="remember">
			<input id="remember" name="remember" type="checkbox" /> Remember me
		</label>
	</div>
	
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	<input type="submit" name="submit" value="Login" />

	
</form>