<?php require_once('core/init.php');

  echo Session::flash('home');
  $user = new User(); 
  
  
  
  
  if($user->isLoggedin()){
	  
	  echo ' <p>Hello <a href="profile.php?username='.$user->data()->username.'" >'.$user->data()->username.'</a></p>';
	  echo ' <p><a href="update.php">Update</a></p>';
	  echo ' <p><a href="changepassword.php">Change Paddword</a></p>';
	  echo ' <p><a href="logout.php">Logout</a></p>';
	  if($user->hasPermission('admin')){
		  echo '<p>You are admin</p>';
	  }
	  
  }else{
	 echo 'you need to '; 
	 echo '<a href="login.php">login</a> or '; 
	 echo '<a href="register.php">register</a>'; 
  }