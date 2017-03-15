<?php
/**
 * 反射类
 * @author wave
 */

class Ref{

	/**
	 * 类名
	 * @author wave
	 */
	static  public  $class;

	/**
	 * 方法名
	 * @author wave
	 */
	static  public  $method;
	
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
	 * @return object
	 * @author wave
	 */
	static public function classInstace(){
		self::$classRef = new ReflectionClass(self::$class);
	}

	/**
	 * 初始化反射类方法
	 * @return object
	 * @author wave
	 */
	static public function methodInstace(){
		self::$methodRef = new ReflectionMethod(self::$class,self::$method);
	}

	/**
	 * 判断类方法是否存在
	 * @return object
	 * @author wave
	 */
	static public function hasMethod(){
		return self::$classRef->hasMethod(self::$method); 
	}

	/**
	 * 获取类方法
	 * @return object
	 * @author wave
	 */
	static public function getMethod(){
	 	return self::$classRef->getMethod(self::$method);
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

//演示示例
//Ref::$class = "a";
//Ref::$method = "test1";
//Ref::classInstace();
//echo Ref::hasMethod();
//Ref::methodInstace();
//Ref::invokeArgs(array('aaaa',11));
