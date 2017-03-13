<?php
/**
 * 视图API
 * @author wave
 */

class ViewApi {

	static public $view = '';

	static public $params = array();

	/**
 	 * 视图初始化
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