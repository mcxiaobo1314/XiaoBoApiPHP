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
			self::setPathAutoLoad($class_name);
		}
	}

	/**
	 * 设置自动加载目录
	 * @param string $class_name  文件名
	 * @author wave
	 */
	public static function setPathAutoLoad($class_name){
		if(class_exists('Bootstrap') && !empty(Bootstrap::$autoLoadPath)) {
			foreach (Bootstrap::$autoLoadPath as $value) {
				self::strposAutoload($class_name,$value);
			}		
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
	}

}
