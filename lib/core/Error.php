<?php
/**
 * 错误类
 * @author wave
 */


class XiaoBoError {

	/**
	 * 获取url参数
	 * @author wave
	 */
	public static $urlPamas = array();

	/**
	 *  错误级别
	 * @author wave
	 */
	public static $level = array(
		 '1' => 'E_ERROR',
		 '2' => 'E_WARNING',
		 '4' => 'E_PARSE',
		 '8' => 'E_NOTICE',
		 '256' => 'E_USER_ERROR',
		 '512' => 'E_USER_WARNING',
		 '1024' => 'E_USER_NOTICE',
		 '2048' => 'E_STRICT',
		 '8191' => 'E_ALL'
	);
	
	/**
	 *  错误信息数组
	 * @author wave
	 */
	public static $errArr = array();


	/**
	 * 初始化错误类
	 * @author wave
	 */
	public static function init(){
		set_error_handler('XiaoBoError::ErrorHandler');
		register_shutdown_function("XiaoBoError::echoErr");
	}

	/**
	 * 抛出错误头信息
	 * @param int $errno 错误级别
	 * @param string $errstr 错误信息
	 * @param string $errfile 错误文件路径
	 * @param int $errline 错误行数
	 * @author wave
	 */
	public static function ErrorHandler($errno,$errstr, $errfile, $errline){
		self::$errArr = array('type'=>$errno,'message'=>$errstr,'file'=>$errfile,'line'=>$errline);
		self::getError();
	}

	/**
	 * 抛出错误信息
	 * @author wave
	 */
	public static function echoErr(){
		self::$errArr =  error_get_last();
		self::getError();
	}

	/**
	 * 写入错误日志
	 * @param string $message 错误消息
	 * @param string $file 文件名称
	 * @return bool
	 * @author wave
	 */
	public static function log($message = "",$file = "error.log"){
		$serverTime = Server::get("REQUEST_TIME");
		if(ERROR_PATH){
			$path = ERROR_PATH . $file;
			$error = "server time:[".$serverTime ."]-url Pamas:[".Server::get('REQUEST_URI')."]-message:".$message;
			return file_put_contents($path,$error."\r\n",FILE_APPEND);
		}
	}
	
	/**
	 * 获取错误信息
	 * @author wave
	 */
	public static function getError(){
		if(!empty(self::$errArr)){
			if(isset(self::$level[self::$errArr['type']]))
				self::$errArr ['message'] =  self::$level[self::$errArr['type']].":".self::$errArr['message'];
			throw new XiaoBoException(self::$errArr['message'],self::$errArr);
		}
	}


}
