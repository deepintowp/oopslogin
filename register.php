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
						'min'=>4,
						'max'=>20,
						'unique'=> 'users',
					),
					'password'=>array(
						'required'=>true,
						'min'=>6,
						'max'=>20,
					),
					'password_again'=>array(
						'required'=>true,
						'min'=>6,
						'max'=>20,
						'matches'=> 'password'
					),
					'name'=>array(
						'required'=>true,
						'min'=>4,
						'max'=>50,
						
					),
	
	
	));
	
	if($validation->passed()){
	
		
		$User = new User();
		 $salt = Hash::salt(32);
		 
		try{
			$User->create(array(
				'username'=>Input::get('username'),
				'password'=>Hash::make(Input::get('password'), $salt ),
				'salt'=>$salt,
				'name'=>Input::get('name'),
				'joined'=>date('Y-m-d H:i:s'),
				'group'=>1,
			
			
			));
			Session::flash('home', 'You Have successfully Register.');
			Redirect::To('index.php');
		}catch(Exception $e){
			die($e->getMessage('Problem Creating user.'));
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
<form method="post" action="">
	<div class="field">
		<label for="username">Username</label>
		<input id="username" name="username" value="<?php echo escape( Input::get('username')); ?>" type="text" autocomplete="off" />
	</div>
	<div class="password">
		<label for="password">Password</label>
		<input  value="<?php echo  escape( Input::get('password')); ?>" id="password" name="password" type="password" autocomplete="off" />
	</div>
	
	<div class="password_again">
		<label for="password_again">Enter Password Again</label>
		<input   value="<?php echo  escape( Input::get('password_again')); ?>"  id="password_again" name="password_again" type="password" autocomplete="off" />
	</div>
	<div class="name">
		<label for="name">Enter Name</label>
		<input   value="<?php echo  escape( Input::get('name')); ?>"  id="name" name="name" type="text" autocomplete="off" />
	</div>
	<input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
	<input type="submit" name="submit" value="Register" />

</form>

