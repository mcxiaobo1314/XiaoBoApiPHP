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
	 * @param string $prefix 类前缀
	 * @author wave
	 */
	public static function instace($class,$method = '',$params = array(),$prefix = ''){
		$pclass = $prefix.$class;
		if(empty(self::$app[$pclass]) && class_exists($class)){
			self::$app[$pclass] =  new $class();
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
		if(empty(self::$app[$class.'_'.$method]) && class_exists($class)){
			Ref::methodInstace($class,$method);
			if(Ref::isPublic() && Ref::isStatic()){
				$class::$method();
				self::$app[$class.'_'.$method] = true;
			}else {
				throw new XiaoBoException($class.'::'.$method."不是公共的静态方法");
			} 
		}
	}

	/**
	 * 注入实例化
	 * @param string $key 要绑定的key
	 * @param string/function $value 要实例化的值
	 * @param array $params 要初始化的参数
	 * @author wave
	 */
	public static function set($key,$value,$params = array()){
		if(empty(self::$app[$key]) && is_string($value)){
			Ref::classInstace($value);
			self::$app[$key] = empty($params) ? Ref::instance() :  Ref::instanceArgs($params);
		}else if(empty(self::$app[$key]) && is_callable($value)) {
			self::$app[$key] = !empty($params) ? call_user_func_array($value,$params) : 
					call_user_func_array($value,array(self::$app));
		}
	}

	
	/**
	 * 获取实例化
	 * @param string $key 要绑定的key
	 * @author wave
	 */
	public static function get($key){
		if(isset(self::$app[$key])){
			return self::$app[$key];
		}
	}


	/**
	 * 删除实例化
	 * @param string $key 要删除的key
	 * @author wave
	 */
	public static function del($key){
		if(isset(self::$app[$key])){
			unset(self::$app[$key]);
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
