<?php
require_once('core/init.php');
$User = new User();
if(!$User->isLoggedin()){
	Redirect::To('index.php');
}
if(Input::exists() && Token::check(Input::get('token'))  ){
	$validate = new Validation();
	$validation = $validate->check($_POST, array(
					
					'name'=>array(
						'required'=>true,
						'min'=>4,
						'max'=>50,
						
					),
					
					
				));
	if($validation->passed()){
		try{
			$User->update(array('name' => Input::get('name') ));
		}catch(Exception $e ){
			die($e->getMessage());
		}
		Session::flash('home', 'Your profile Has been updated.');
		Redirect::To('index.php');
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
		<label for="name">Name</label>
		<input name="name" value="<?php echo $User->data()->name; ?>" id="name" type="text" />
	</div>
	<input type="hidden"  value="<?php echo Token::generate(); ?>" name="token" />
	<input type="submit" value="Update" name="update" />
	
</form>