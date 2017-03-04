<?php

class WpUsers extends ModelApi{

	public $aa;

	public function inits($aa){
		$this->aa = $aa;
	}

	public function test(){
		return $this->aa;
		//return self::LoadModel('wp_users')->find();
	}
}