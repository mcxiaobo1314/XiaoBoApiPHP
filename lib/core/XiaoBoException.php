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
 		if(isset($this->getTrace[0])){
 			$this->errorHtml($this->getTrace[0],$this->message);
 		}
	}


	/**
	 * 输出错误html
	 * @param array $trace 异常数组
	 * @param string $messages 异常信息
	 * @author wave
	 */
	private function errorHtml($trace , $messages = ""){
		header("Content-Type:text/html;charset=utf-8");
		$html = "<div style='padding:0px;margin:0px; width:80% height:80%; margin:0 auto;margin-top:10%; background:#DDDDDD;'>";
		$html .= "<dd style='text-align:center;'><h2>XiaoBoApiPHP框架提醒您错误信息:【{$messages}】</h2></dd>";
		$html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>文件路径:{$trace['file']}</dt>";
		$html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>第:{$trace['line']}行</dt>";
		$html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>类名:{$trace['class']}</dt>";
		$html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>方法名:{$trace['function']}</dt>";
		$html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>错误代码区:</dt>";
		$html .= "<dt style='text-indent:60px; width:10% height:30px; line-height:30px; background:#888888;'>
			".$this->showErrorPhp($trace['file'],$trace['line'])."
			</dt>";
		$html .= "<dt style='text-align:right; width:100% height:30px; line-height:30px;'>QQ群：114252528</dt>";
		$html .= "<dt style='text-align:right; width:100% height:30px; line-height:30px;'><a href='https://www.github.com/mcxiaobo1314' style='color:black;'>github</a> | <a style='color:black;' href='http://xbphp.nmfox.com'>官网</a></dt>";
		$html.= "</div>";
		exit($html);
	}

	/**
	 * 获取错误代码
	 * @param string $file 错误文件路径
	 * @param int $line 错误行数
	 * @return string
	 * @author wave
	 */
	private function showErrorPhp($file,$line){
		$conntents = file_get_contents($file);
		$connArr = explode("\r\n", $conntents);
		$sum = count($connArr);
		$data = "";
		if($line - 5 < 0){
			$start = 0;
		}
		if($line - 5 >= 0){
			$start = $line - 5;
		}
		if( $line + 5 <= $sum ){
			$end = $line +5;
		}
		if($line+5 > $sum){
			$end = $line + ($sum - $line);
		}
		for($i = $start; $i<=$end; $i++){
			$data .= "<li style='list-style:none;color:#227700;'>第".$i."行:".$connArr[$i]."</li>";
		}
		return $data;
	}




}
