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
	 * 初始化错误类
	 * @author wave
	 */
	public static function init(){
		register_shutdown_function("XiaoBoError::echoErr");
	}


	/**
	 * 抛出错误信息
	 * @author wave
	 */
	public static function echoErr(){
		$errArr = error_get_last();
		if(!empty($errArr)){
			if(isset(self::$level[$errArr['type']]))
				$errArr['message'] =  self::$level[$errArr['type']].":".$errArr['message'];
			throw new XiaoBoException($errArr['message'],$errArr);
		}
	}

	/**
	 * 写入错误日志
	 * @param string $message 错误消息
	 * @param string $file 文件名称
	 * @return bool
	 * @author wave
	 */
	public static function log($message = "",$file = "error.log"){
		$serverTime = $_SERVER["REQUEST_TIME"];
		$path = dirname(__FILE__).ROUTE_DS.'..'.ROUTE_DS.'..'.ROUTE_DS.APP_ROOT_PATH.ROUTE_DS.$file;
		$error = "server time:[".$serverTime ."]-url Pamas:[".Server::get('REQUEST_URI')."]-message:".$message;
		return file_put_contents($path,$error."\r\n",FILE_APPEND);
	}


}
