<?php
/**
 * 视图API
 * @author wave
 */

class ViewApi {


	/**
 	 * 视图初始化
 	 * @param array $params 获取路由url参数
 	 * @author wave
 	 */
 	static public function init() {
 		if(load(dirname(__FILE__).'/'.'View.php')){
 			Container::instace('View');
		}
 	}

}
ViewApi::init();

?>
