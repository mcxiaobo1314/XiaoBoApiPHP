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
 	 * 视图初始化
 	 * @param array $params 获取路由url参数
 	 * @author wave
 	 */
 	static public function init() {
 		require dirname(__FILE__).'/'.'View.php';
		Ref::classInstace('View');
		self::$view= Ref::instance();
 	}

}
ViewApi::init();

?>
