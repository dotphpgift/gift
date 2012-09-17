<?php defined('ACCESS') or die("No direct script access allowed");

class DOffline extends CApplicationComponent
{
	 public $allowedIPs=array();
	 public $locked=false;
	 public $redirectURL='';

	public function init()
	{
   		// check if ip is blocked...
   		if($this->locked == true){
   			$allowed = false;
   			$ips=$this->allowedIPs;
   			foreach($ips as &$ip){
   				if($_SERVER['REMOTE_ADDR'] == $ip){
   					$allowed = true;
   				} 
   			}
			if($allowed == true)
			{
				//do nothing
			}
			else{
				DBase::getApp('request')->redirect($this->redirectURL);
			}   		
   		}
	}
}