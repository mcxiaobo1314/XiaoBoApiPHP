<?php
/**
 * 反射类
 * @author wave
 */

class Ref{

	static  public  $class;

	static  public  $method;


	static  protected $classRef;


	static  protected $methodRef;


	static public function classInstace(){
		self::$classRef = new ReflectionClass(self::$class);
	}

	static public function methodInstace(){
		self::$methodRef = new ReflectionMethod(self::$class,self::$method);
	}




	static public function hasMethod(){
		return self::$classRef->hasMethod(self::$method); 
	}


	static public function getMethod(){
	 	return self::$classRef->getMethod(self::$method);
	}


	static public function instance(){
		return self::$classRef->newInstance();
	}

	static public function invokeArgs($params = array()){
		return self::$methodRef->invokeArgs(new self::$class(),$params);
	}
}

class a{
	public function test($name,$id){
		echo "name=".$name.",id=".$id;
	}
}

Ref::$class = "a";
Ref::$method = "test1";
Ref::classInstace();
echo Ref::hasMethod();
//Ref::methodInstace();
//Ref::invokeArgs(array('aaaa',11));
