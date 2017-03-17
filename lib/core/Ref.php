<?php
/**
 * 反射类
 * @author wave
 */

class Ref{

	/**
	 * 保存反射类对象
	 * @author wave
	 */
	static  protected $classRef;

	/**
	 * 保存反射类方法对象
	 * @author wave
	 */
	static  protected $methodRef;


	/**
	 * 初始化反射类
	 * @param string $class 类名 
	 * @return object
	 * @author wave
	 */
	static public function classInstace($class = ''){
		self::$classRef = new ReflectionClass($class);
	}

	/**
	 * 初始化反射类方法
	 * @param string $class 类名 
	 * @param string $method 方法名 
	 * @return object
	 * @author wave
	 */
	static public function methodInstace($class = '',$method = ''){
		self::$methodRef = new ReflectionMethod($class,$method);
	}

	/**
	 * 判断类方法是否存在
	 * @param string $method 方法名 
	 * @return object
	 * @author wave
	 */
	static public function hasMethod($method = ''){
		return self::$classRef->hasMethod($method); 
	}

	/**
	 * 获取类方法
	 * @param string $method 方法名 
	 * @return object
	 * @author wave
	 */
	static public function getMethod($method = ''){
	 	return self::$classRef->getMethod($method);
	}
	
	/**
	 * 类初始化
	 * @return object
	 * @author wave
	 */
	static public function instance(){
		return self::$classRef->newInstance();
	}

	/**
	 * 对初始类方法进行传递参数
	 * @param array $params 参数
	 * @return object
	 * @author wave
	 */
	static public function invokeArgs($params = array(),$object = ''){
		if($object){
			return self::$methodRef->invokeArgs($object,$params);
		}
		return self::$methodRef->invokeArgs(self::instance(),$params);
	}
}

