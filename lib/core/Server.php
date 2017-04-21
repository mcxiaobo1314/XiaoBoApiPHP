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

	/**
	 * 判断是否ajax请求
	 * @return bool
	 * @author wave
	 */
	static public function isAjax(){
		return strtolower(self::get('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
	}
	
	/**
	 * 设置header信息
	 * @param string @contents  头部内容
	 * @return 
	 * @author wave
	 */
	static public function headers($contents){
		return header($contents);
	}

	/**
	 * 判断是否post请求
	 * @return bool
	 * @author wave
	 */
	static public function isPost(){
		return strtolower(self::get('REQUEST_METHOD')) == 'post';
	}

	
	/**
	 * 判断是否Get请求
	 * @return bool
	 * @author wave
	 */
	static public function isGet(){
		return strtolower(self::get('REQUEST_METHOD')) == 'get';
	}
	
	/**
	 * 判断是否head请求
	 * @return bool
	 * @author wave
	 */
	static public function isHead(){
		return strtolower(self::get('REQUEST_METHOD')) == 'head';
	}

	/**
	 * 判断是否put请求
	 * @return bool
	 * @author wave
	 */
	static public function isPut(){
		return strtolower(self::get('REQUEST_METHOD')) == 'put';
	}	
}
