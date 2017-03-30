<?php
/**
 * 初始化核心类文件
 * @author wave
 */

class Bootstrap {

	/**
	 * 初始化函数
	 * @author wave
	 */
	public static function instace(){
		//初始化自由组装组件
		Container::instace('XmlParse');

		$XmlParse = Container::get('XmlParse');

		//初始化XML解析器
		$XmlParse->init("Bootstrap::load");
	}

	/**
	 * 回掉函数加载类进行api初始化
	 * @author wave
	 */
	public static function load(){
		//视图加载
		ViewApi::init();
		//初始化路由类对象
		RouteApi::init();
		//加载路由配置文件
		load(str_replace('\\', '/', dirname(__FILE__)).'/../conf/Route.php');  
		//获取路由参数
		RouteApi::getRoute();
	}

}
