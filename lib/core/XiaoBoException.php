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

	protected static  $errNum = 0;

	/**
	 * 构造函数
	 * @param String $message 异常消息
	 * @param arry  $trace 异常信息数组
	 * @author wave
	 */
	public function __construct($message = '',$trace = array()) {
		if(!empty($message)){
			$this->message = $message;
		}
		++self::$errNum;
		parent::__construct($this->message);
		($trace !== false) &&  XiaoBoError::log($this->message);
		$this->getTrace = !empty($trace) ? array($trace) : $this->getTrace();
	}
 
 	/**
 	 * 转换成字符串进行输出
 	 * @author wave
 	 */
	public function __toString() {
 		if(isset($this->getTrace[0]) && self::$errNum === 1){
 			$this->errorHtml($this->getTrace[0],htmlspecialchars($this->message));
 		}
	}


	/**
	 * 输出错误html
	 * @param array $trace 异常数组
	 * @param string $messages 异常信息
	 * @author wave
	 */
	private function errorHtml($trace , $messages = ""){
		$html  = "<html><head><meta charset='utf-8'></meta><title>XiaoBoApiPHP框架提醒您</title></head><body>";
		$html .= "<div style='padding:0px;margin:0px; width:80% height:80%; margin:0 auto;margin-top:10%; background:#DDDDDD;'>";
		if(DEBUG){
			$html .= "<dd style='text-align:center;'><h2>XiaoBoApiPHP框架提醒您:【{$messages}】</h2></dd>";
			isset($trace['file']) && $html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>文件路径:{$trace['file']}</dt>";
			isset($trace['line']) && $html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>第{$trace['line']}行</dt>";
			isset($trace['file']) && isset($trace['class']) && $html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>类名:{$trace['class']}</dt>";
			isset($trace['file']) && isset($trace['function']) && $html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>方法名:{$trace['function']}</dt>";
			isset($trace['line']) &&  isset($trace['file']) && $html .= "<dt style='text-indent:30px; width:10% height:30px; line-height:30px; '>错误代码区:</dt>";
			isset($trace['line']) &&  isset($trace['file']) && $html .= "<dt style='text-indent:60px; width:10% height:30px; line-height:30px; background:#888888;'>
				".$this->showErrorPhp($trace['file'],$trace['line'])."
				</dt>";
		}else {
			Server::headers('HTTP/1.0 404 not found');
			$html .= "<dd style='text-align:center;'><h2>XiaoBoApiPHP框架提醒您:【该页面不存在或者被删除了】</h2></dd>";
		}
		$html .= "<dt style='text-align:right; width:100% height:30px; line-height:30px;'>QQ群：114252528</dt>";
		$html .= "<dt style='text-align:right; width:100% height:30px; line-height:30px;'><a href='https://www.github.com/mcxiaobo1314' style='color:black;'>github</a> | <a style='color:black;' href='http://xbphp.nmfox.com'>官网</a></dt>";
		$html.= "</div></body></html>";
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
		$connArr = $this->read($file);
		$sum = count($connArr);
		$data = "";
		if($line - 8 < 0){
			$start = 0;
		}
		if($line - 8 >= 0){
			$start = $line - 8;
		}
		if( $sum - ($line +5) >= 0 ){
			$end = $line +5;
		}
		if($sum - ($line +5) < 0){
			$end = $line + ($sum - $line);
		}
		for($i = $start; $i<=$end; $i++){
			if($i === $line){
				$data .= "<li style='list-style:none;background:#FF8888;'>第".$i."行:".htmlspecialchars($connArr[$i])."</li>";
			}else {
				$data .= "<li style='list-style:none;color:#227700;'>第".$i."行:".htmlspecialchars($connArr[$i])."</li>";
			}
		}
		return $data;
	}


	/**
	 * 读取数据数据
	 * @param String $path 文件路径
	 * @param Sring $m 打开模式
	 * @param int $size 读取的数据字节
	 * @return Array
	 * @author wave
	 */
	private function read($path,$m ='r',$size=1024) {
		$valArr = array();
		if(file_exists($path)) {
			$fp = fopen($path,$m);
			while(!feof($fp)) {
				$valArr[] = fgets($fp);
			}
			fclose($fp);
			return $valArr;
		}
	}


}
