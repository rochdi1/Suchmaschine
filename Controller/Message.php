<?php
namespace Controller;
class Message{
	
	private static $switch = FALSE;
	
	public static function on(){
		self::$switch = TRUE;
	}
	
	public static function off(){
		self::$switch = FALSE;
	}
	
	public static function send($message){
		if(self::$switch){
			echo $message . "\n";
		}
	}
}

?>