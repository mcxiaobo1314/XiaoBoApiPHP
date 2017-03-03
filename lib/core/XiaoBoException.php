<?php
/**
 * 自定义异常类
 * @author wave
 */

class XiaoBoException extends Exception {

	/**
	 * 异常消息
	 * @author wave
	 */
	public $message = 'Unknown exception';
	
	/**
	 * 获取异常信息数组
	 * @author wave
	 */
	public $getTrace = array();

	/**
	 * 构造函数
	 * @param String $message 异常消息
	 * @param int  $code 异常代码行数
	 * @author wave
	 */
	public function __construct($message) {
		ini_set('display_errors','Off');
		error_reporting(0);
		$this->message = $message;
		parent::__construct($this->message);
		$this->getTrace = $this->getTrace();
	}
 
 	/**
 	 * 转换成字符串进行输出
 	 * @author wave
 	 */
	public function __toString() {
		$errStr = '';
		if(!empty($this->message)) {
			$errStr .= 'XiaoBoPHP框架提醒您錯誤信息:' . $this->message;
 		}
		if(isset($this->getTrace[0]['file'])) {
			$errStr .= ';错误的路径:' . $this->getTrace[0]['file'];
 		}
 		if(isset($this->getTrace[0]['line'])) {
			$errStr .= ';错误的第:' . $this->getTrace[0]['line'].'行';
 		}
		if(isset($this->getTrace[0]['class'])) {
			$errStr .= ';错误的类名:' . $this->getTrace[0]['class'];
 		}
		if(isset($this->getTrace[0]['function'])) {
			$errStr .= ';错误的方法名:' . $this->getTrace[0]['function'];
 		}
		exit($errStr);
	}
}
