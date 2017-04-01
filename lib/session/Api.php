<?php
/**
 * session api类
 * @author wave
 */

class SessionApi{

	/**
	 * 构造函数
	 * @author wave
	 */
	public function __construct(){
		load(dirname(__FILE__).'/Session.php');
		Container::bind('sess','Session'); 
		Container::make('sess');
		$sess = Container::get('sess',true);
		//ini_set("session.save_handler","files"); 
		session_set_save_handler(  
            array($sess,'open'),  
            array($sess,'remove'),  
            array($sess,'read'),  
            array($sess,'write'),  
            array($sess,'destroy'),
            array($sess,'gc')  
        );
       // session_start();  
		//$sess->write('a','cc11');
		//$sess->write('b','ddd11');
       // $sess->gc();
		//var_dump($sess->read());
		//$sess->destroy();
		//$sess->remove('b');
		//var_dump($sess->read());
	}

}
Container::instace('SessionApi');