<?php
/**
 * 视图API
 * @author wave
 */

class ViewApi {

	static public $view = '';

	/**
 	 * 视图初始化
 	 * @author wave
 	 */
 	static public function init() {
 		require dirname(__FILE__).'/'.'View.php';
 		self::$view =  new View();
 	}

}
ViewApi::init();

?>