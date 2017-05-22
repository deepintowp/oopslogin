<?php 

class User{
	private $_db, $_data, $sessionNane, $cookieNane;
	private $_isLoggedin = false;
	public function __construct($user = null ){
		$this->_db = DB::getInstance();
		$this->sessionNane = Config::get('session/session_name');
		$this->cookieNane = Config::get('remember/cookie_name');
		if(!$user){
			if(Session::exists($this->sessionNane)){
				$user = Session::get($this->sessionNane);
				if($this->find($user)){
					$this->_isLoggedin = true;
				}else{
					//process log out
				}
			}
		}else{
			$this->find($user);
		}
		
		
	}
	
	public function create($fields = array()){
		if(!$this->_db->insert('users', $fields)){
			throw new Exception('There was a problem');
		}
	}
	
	public function hasPermission($capabilty){
		$group = $this->_db->get('groups', array('id', '=', $this->data()->group ));
		if($group->count()){
			$permissions =  json_decode( $group->first()->permission, true);
			
			if($permissions[$capabilty] == true){
				return true;
			}
		}
		return false;
	}
	
	
	
	
	public function login($username = null, $password  = null , $remember = false){
		if(!$username && !$password && $this->exists() ){
			
			Session::put($this->sessionNane, $this->data()->id );
			
		}else{
				$user = $this->find($username);
				if($user){
				if($this->data()->password === Hash::make($password, $this->data()->salt ) ){
					Session::put($this->sessionNane, $this->data()->id);
					if($remember){
						$hash = Hash::unique();
						$hashcheck = $this->_db->get('users_session', array('user_id', '=', $this->data()->id ));
						if(!$hashcheck->count()){
							$this->_db->insert('users_session', array(
									'user_id' => $this->data()->id,
									'hash' => $hash
							
							));
						}else{
							$hash = $hashcheck->first()->hash;
						}
						
						Cookie::put($this->cookieNane, $hash, Config::get('remember/cookie_expiry') );
					}
					return true;
				}
			}
	}
		return false;
	}
	
	public function find($user = null){
		$field = is_numeric($user)? 'id' : 'username';
		$data = $this->_db->get('users', array($field, '=', $user) );
		if($data->count()){
			$this->_data = $data->first();
			return true;
		}
		
		return false;
	}
	public function logout(){
		
		$this->_db->delete('users_session', array('user_id', '=', $this->_data->id ));
		Session::delete($this->sessionNane);
		Cookie::delete($this->cookieNane);
		
	}
	public function update($fields =  array(), $id = null ){
		if(!$id && $this->isLoggedin()){
			$id = $this->data()->id;
		}
		
		
		if(!$this->_db->update('users', $fields, array('id'=> $id) ) ){
			throw new Exception('There was a problem updating.'); 
		}
	}
	
	
	public function data(){
		return $this->_data;
	}
	public function isLoggedin(){
		return $this->_isLoggedin;
	}
	public function exists(){
		return !empty($this->_data)? true : false ;
	}
}