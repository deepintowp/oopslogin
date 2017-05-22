<?php 

require_once('core/init.php');
$User = new User();
if(!$User->isLoggedin()){
	Redirect::To('index.php');
}
$User->logout();
Redirect::To('index.php');