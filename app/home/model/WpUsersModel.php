<?php

class WpUsers extends ModelApi{

	public function test(){
		return self::LoadModel('wp_users')->find();
	}
}