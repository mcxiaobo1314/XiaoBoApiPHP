<?php
 /**
  * 路由加载API
  * @author wave
  */

class RouteApi {

	/**
	 * 初始化路由
	 * @author wave
	 */
 	static protected $route;

 	/**
 	 * 标示是否定义路由别名
 	 * @author wave
 	 */
 	static protected $flag = true;

 	/**
 	 * 路由初始化
 	 * @author wave
 	 */
 	static public function init() {
 		require dirname(__FILE__).'/'.'Route.php';
 		self::$route = new Route();
 	}

 	/**
 	 * 获取路由URL
 	 * @return Array
 	 * @author wave
 	 */
 	static public function getRoute() {
 		return self::$route->init();
 	}

	/**
	 * 设置路由别名
	 * @param string $url 路由别名
	 * @param string $g  分组
	 * @param string $c  类名
	 * @param string $a 方法名
	 * @param Array  $params  参数
	 * @author wave
	 */
 	static public function aliasRoute($url='',$g= '',$c= '' ,$a = '',$params = array()){
 		self::$flag = false;
 		if(rtrim(self::$route->getUrlParam(self::$flag),'/') == rtrim($url,'/')){
 			//ViewApi::$view->setRoute($g,$c,$a);
 			self::$route->setRoute($g,$c,$a,$params);
 			
 		}
 	}


 	/**
 	 * 获取当前url参数
 	 * @return string
 	 * @author wave
 	 */
 	static public function getUrl(){
 		return self::$route->getUrlParam(self::$flag);
 	}

 	/**
 	 * 获取当前url分组
 	 * @return string
 	 * @author wave
 	 */
 	static public function getGroup(){
 		return self::$route->groupName;
 	}

 	/**
 	 * 获取当前url类名
 	 * @return string
 	 * @author wave
 	 */
 	static public function getClass(){
 		return self::$route->className;
 	}

 	/**
 	 * 获取当前url方法名
 	 * @return string
 	 * @author wave
 	 */
 	static public function getAction(){
 		return self::$route->actionName;
 	}

 	/**
 	 * 获取当前域名
 	 * @return string
 	 * @author wave
 	 */
 	static public function getHost(){
 		return self::$route->host;
 	}

	/**
 	 * 获取当前scheme
 	 * @return string
 	 * @author wave
 	 */
 	static public function getScheme(){
 		return self::$route->scheme;
 	}
 	
 }
//初始化路由类对象
RouteApi::init();
require  str_replace('\\', '/', dirname(__FILE__)).'/../conf/Route.php';  
//获取路由参数
RouteApi::getRoute();
