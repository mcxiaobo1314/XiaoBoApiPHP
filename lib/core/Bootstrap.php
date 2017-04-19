<?php
/**
 * 启动初始化加载文件
 * @author wave
 */

class Bootstrap {

	/**
	 * 设置自动加载文件路径(要写相对路径) 自动加载，类名和文件名必须保持一致，否则加载失败
	 * @author wave
	 */
	static public $autoLoadPath = array(
		//'thinkphp'=>"/thinkphp/" //自动加载例子
	);


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
