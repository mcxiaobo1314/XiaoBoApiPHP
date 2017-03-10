<?php
 /**
  * 路由加载API
  * @author wave
  */

class RouteApi {

 	static protected $route;

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
 		if(rtrim(self::$route->getUrlParam(false),'/') == rtrim($url,'/')){
 			ViewApi::$view->setRoute($g,$c,$a);
 			self::$route->setRoute($g,$c,$a,$params);
 			
 		}
 	}

 }
//初始化路由类对象
RouteApi::init();
require  str_replace('\\', '/', dirname(__FILE__)).'/../conf/Route.php';  
//获取路由参数
RouteApi::getRoute();
