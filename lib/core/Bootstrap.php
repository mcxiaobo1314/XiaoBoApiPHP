<?php
/**
 * 启动初始化加载文件
 * @author wave
 */
class Bootstrap {

	/**
	 * 设置自动加载文件路径(写绝对路径)
	 * @author wave
	 */
	static public $autoLoadPath = array();


	/**
	 * 启动初始化程序
	 * @param string $config 获取配置文件属性名
	 * @author wave 
	 */
	static public function  app($config){
		switch ($config) {
			case 'view':
				ViewApi::init();
				break;
			case 'route':
				//初始化路由类对象
				RouteApi::init();
				//加载路由配置文件
				load(str_replace('\\', '/', dirname(__FILE__)).'/../conf/Route.php');  
				//获取路由参数
				RouteApi::getRoute();
				break;
		}
	}
}
