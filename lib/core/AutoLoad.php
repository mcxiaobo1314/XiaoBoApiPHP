<?php
/**
 * 自动加载类
 * @author wave
 */

class AutoLoads {

	/**
	 * 初始化
	 * @author wave
	 */
	public static function init() {
		spl_autoload_register('self::autoload');
	}

	/**
	 * 自动加载核心文件(非静态类)
	 * @param string $class_name  文件名
	 * @author wave
	 */
	public static function autoload($class_name) {
		if($class_name != 'AutoLoad') {
			self::strposAutoload($class_name,str_replace('\\', '/', dirname(__FILE__)).'/');
		}
	}
	
	/**
	 * 截取文件名并判断加载文件是否存在
	 * @param string $class_name 文件名
	 * @param string $path 加载的路径
	 * @param string $extension 扩展名
	 * @author wave
	 */
	public static function strposAutoload($class_name,$path,$extension = '.php') {
		$class_name = ($class_name == "XmlParse") ? "Xml" : $class_name;
		$class_name = ($class_name == 'XiaoBoError') ? 'Error' : $class_name;
		if(file_exists($path.$class_name.'.php')) {
			require $path.$class_name.$extension;
		}
		//throw new XiaoBoException('引入文件失败');
	}

}
