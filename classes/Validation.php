<?php 


class Validation{
	private $_passed = false,
			$_errors =  array(),
			$_db = null;
			
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	
	public function check($source, $items=array()){
		foreach($items as $item=>$rules){
			foreach($rules as $rule=>$rule_value){
				//echo "$item $rule must be $rule_value <br />";
				$value = trim($source[$item]);
				
				if($rule === 'required' && empty($value)){
					$this->addError("{$item} is required.");
					
				}else if(!empty($rule)){
					switch ($rule){
						case 'min':
							if( strlen($value)  < $rule_value ){
								$this->addError("{$item} must be at least {$rule_value} charecters.");
								
							}
							
						break;
						case 'max':
							if( strlen($value)  > $rule_value ){
								$this->addError("{$item} must not  {$rule_value} charecters more.");
								
							}
						break;
						
						case 'matches':
							if( $value !== $source[$rule_value] ){
							$this->addError("{$item} must matches with  {$rule_value}.");
							
							}
						break;
						
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $source[$item] ) )->count();
							if($check){
								$this->addError("This {$item} already exist.");
								
							}
						break;
						
						
						
					}
				}
			}
			
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}
	
	private function addError($error){
		$this->_errors[] = $error;
	}
	
	public function error(){
		return $this->_errors;
	}
	public function passed(){
		return $this->_passed;
	}
}

