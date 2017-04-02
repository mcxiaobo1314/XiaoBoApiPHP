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
		Container::bind('sess','FileSessionHandler'); 
		Container::make('sess');
		$sess = Container::get('sess',true);
		//ini_set("session.save_handler","files"); 
		session_set_save_handler(  
            array($sess,'open'),  
            array($sess,'close'),  
            array($sess,'read'),  
            array($sess,'write'),  
            array($sess,'destroy'),
            array($sess,'gc')  
        );
        session_start();  
        $sess->sessId .= $sess->suffix.session_id();
        $sess->isPath();
	}

}
Container::instace('SessionApi');