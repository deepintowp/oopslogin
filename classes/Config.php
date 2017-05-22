<?php 
class Config{
	public static function get($path){
		if($path){
			$config = $GLOBALS['config'];
			$bits = explode('/', $path);
			foreach($bits as $bit){
				if(isset($config[$bit])){
					$config = $config[$bit];
				}
			}
			return $config;
		}
	}
}