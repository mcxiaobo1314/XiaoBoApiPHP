<?php
/**
 * 视图API
 * @author wave
 */

class ViewApi {

	/**
	 * 保存视图
	 * @author wave
	 */
	static public $view = '';

	/**
	 * url参数
	 * @author wave
	 */
	static public $params = array();

	/**
 	 * 视图初始化
 	 * @param array $params 获取路由url参数
 	 * @author wave
 	 */
 	static public function init($params = array()) {
 		self::$params = $params;
 		require dirname(__FILE__).'/'.'View.php';
 		self::$view =  new View();
 	}

}
ViewApi::init(ViewApi::$params);

?>