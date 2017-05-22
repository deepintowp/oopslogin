<?php 
//connecting DB and quering 

class DB{
	private static $_instance = null;
	private $_PDO,
			$_query,
			$_eorror  = false,
			$_results,
			$_count    = 0;
	private function __construct ()
		{    
			try
			{    
			 $this->_PDO =	new PDO( 'mysql:dbname='.Config::get('mysql/db_name').';host='.Config::get('mysql/host'), Config::get('mysql/db_user'), Config::get('mysql/password'));
			
			}
			catch (PDOException $e)
			{
				
				die($e->getMessage());
			}
		}	
// init this class
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
			
		}
		return self::$_instance;
	}		
	public function query($sql, $params=array()){
		$this->_eorror = false;
		if($this->_query = $this->_PDO->prepare($sql)){
			if(count($params)){
				$i = 1;
				foreach($params as $param){
					$this->_query->bindValue($i, $param );
					$i++;
				}
			}
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count 	= $this->_query->rowCount();
			}else{
				$this->_eorror = true;
				$this->_count = 0;
			}
			
		}
		return $this;
	}		
	private function action($action, $table, $where = array()){
		if(count($where) === 3 ){
			$oparetors = array('=','>','<','>=','<=');
			$field = $where[0];
			$oparetor = $where[1];
			$value = $where[2];
			if(in_array($oparetor, $oparetors)){
				$sql = "{$action} FROM {$table} where {$field} {$oparetor} ?";
				if(!$this->query($sql, array($value))->error()){
					return $this; 
				}
			}
		}
		return $this; 
		
	}
	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);
	}
	
	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}
	
	public function insert($tbale, $fields =  array() ){
		if(count($fields)){
			$keys = array_keys($fields);
			$values = '';
			$x = 1;
			foreach($fields as $field){
				$values .= '?';
				
				if(count($fields) > $x){
					$values .= ',';
				}
				$x++;
			}
			
			$sql = "INSERT INTO `{$tbale}` (`".implode('`,`', $keys )."`) VALUES($values) ";
			if(!$this->query($sql, $fields )->error()){
				return true;
			}
		}
		
		return false;
	}
	
	public function update($table,  $fields =  array(), $where =   array() ){
	
	
	if(count($fields)){
		$columntargetTobe = array();
		$value = array();
		foreach($fields as $key => $val ){
			$columntarget = $key;
			$columntarget .= '=';
			$columntarget .= '?';
			$value[] =  $val;
			$columntargetTobe[] = $columntarget;
		}
		$whertargetTobe = array();
		foreach($where as $key => $val ){
			$whertarget = $key;
			$whertarget .= '=';
			$whertarget .= '?';
			$value[] =  $val;
			$whertargetTobe[] = $whertarget;
		}
		
		
		$sql = "UPDATE `{$table}` SET ".implode(',', $columntargetTobe)." WHERE  ".implode(' AND ', $whertargetTobe );
			if(!$this->query($sql, $value )->error()){
				return true;
			}
	}
	return false;
	}
	
	
	public  function first(){
		
		return $this->results()[0];
		
	}
	public  function error(){
		return $this->_eorror;
	}
	
	public  function count(){
		return $this->_count;
	}
	public  function results(){
		return $this->_results;
	}
	
	
	
	
	
	
}