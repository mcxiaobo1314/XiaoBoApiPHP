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
	 * 初始化容器
	 * @param string $class 类
	 * @param string $method 方法
	 * @param array $params 参数  
	 * @author wave
	 */
	public static function instace($class,$method = '',$params = array()){
		if(empty(self::$app[$class]) && class_exists($class)){
			self::$app[$class] = new $class();
		}
		if($method != '' && self::$app[$class] instanceof $class){
			return self::methodInstace(self::$app[$class],$method,$params);
		}
		if(empty(self::$app[$class])){
			throw new XiaoBoException($class."类不存在");
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