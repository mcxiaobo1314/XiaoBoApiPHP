<?php
/**
 * 获取服务器信息类
 * @author wave
 */


class Server {

	/**
	 * 获取server参数值
	 * @param string $name 参数名
	 * @return string
	 * @author wave
	 */
	static public function get($name){
		return isset($_SERVER[$name]) ? $_SERVER[$name] : '';
	}

	/**
	 * 获取cli 参数
	 * @return array
	 * @author wave
	 */
	static public function getCliArgs(){
		return isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
	}

	/**
	 * 判断是否是http请求
	 * @return bool
	 * @author wave
	 */
	static public function isHttp(){
		return self::get('REQUEST_SCHEME') == 'http';
	}

	/**
	 * 判断是否是https请求
	 * @return bool
	 * @author wave
	 */
	static public function isHttps(){
		return self::get('REQUEST_SCHEME') == 'https';
	}
}