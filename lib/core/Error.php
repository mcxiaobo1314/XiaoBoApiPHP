<?php
/**
 * 错误类
 * @author wave
 */


class Error {

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
		ini_set('display_errors','Off');
		register_shutdown_function("Error::echoErr");
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
}