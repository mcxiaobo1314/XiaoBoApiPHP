<?php
/**
 * 容器类
 * @author wave
 */


class Container {

	/**
	 * 初始化保存变量
	 * @author wave
	 */
	public static $app = array();

	/**
	 * 保存静态方法
	 * @author wave
	 */
	public static $staticApp = array();

	/**
	 * 初始化容器
	 * @param string $class 类
	 * @param string $method 方法
	 * @param array $params 参数  
	 * @param string $prefix 类前缀
	 * @author wave
	 */
	public static function instace($class,$method = '',$params = array(),$prefix = ''){
		$pclass = $prefix.$class;
		if(empty(self::$app[$pclass]) && class_exists($class)){
			self::$app[$pclass] = new $class();
		}
		if($method != '' && self::$app[$pclass] instanceof $class){
			return self::methodInstace(self::$app[$pclass],$method,$params);
		}
		if(empty(self::$app[$pclass])){
			throw new XiaoBoException($class."类不存在");
		}
	}

	/**
	 * 静态方法调用
	 * @param string $class 类
	 * @param string $method 方法
	 * @param array $params 参数  
	 * @author wave
	 */
	public static function staticInstace($class,$method = '',$params = array()){
		if(empty(self::$staticApp[$class.'_'.$method]) && class_exists($class)){
			self::$staticApp[$class.'_'.$method] = $class::$method();
		}
	}


	/**
	 * 调用类方法
	 * @param object $obj 对象
	 * @param string $method 方法名
	 * @param array $param 参数
	 * @author wave
	 */
	public static  function methodInstace($obj,$method = '', $params = array()){
		if(is_object($obj) && method_exists($obj, $method)){
			return call_user_func_array(array($obj,$method),$params);
		}
		throw new XiaoBoException($method."方法不存在");
	}

}

?>