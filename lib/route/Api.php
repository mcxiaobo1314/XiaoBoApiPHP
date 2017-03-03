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
 		return self::$route;
 	}



 }

RouteApi::init();