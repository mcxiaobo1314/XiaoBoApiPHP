<?php
/**
 * 容器类
 * @author wave
 */


class Container {

	/**
	 * 注入的数据
	 * @author wave
	 */
	public static $app = array();
	
	/**
	 * 绑定的数据
	 * @author wave
	 */
	private static $bind = array();
	
	/**
	 * 绑定注入的前缀
	 * @author wave
	 */
	private static $bindPrefix = 'bind_';
	
	/**
	 * 被绑定注入的前缀
	 * @author wave
	 */
	private static $bbindPrefix = 'bbind_';

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
					
		if(!isset(self::$app[$pclass]) && class_exists($class)){
			self::$app[$pclass] =  new $class();
		}
		if($method != '' && self::$app[$pclass] instanceof $class){
			return self::methodInstace(self::$app[$pclass],$method,$params);
		}
		if(!isset(self::$app[$pclass])) {
			throw new XiaoBoException($class."类不存在");
		}
		return self::$app[$pclass];
	}

	/**
	 * 注入实例化
	 * @param string $key 要绑定的key
	 * @param string/function $value 要实例化的值
	 * @param array $params 要初始化的参数
	 * @author wave
	 */
	public static function set($key,$value,$params = array()){
		if(isset(self::$app[$key])){
			throw new XiaoBoException("该".$key."值已经注入过了,请先del,再来注入");
		}
		if(is_string($value) && class_exists($value)){
			Ref::classInstace($value);
			self::$app[$key] = empty($params) ? Ref::instance() :  Ref::instanceArgs($params);
		}else if(is_callable($value)) {
			self::$app[$key] = !empty($params) ? call_user_func_array($value,$params) : 
							call_user_func_array($value,array(self::$app));
		}else if((is_string($value) || is_array($value) || is_object($value) ) ){
			self::$app[$key] = $value;
		}
	}
	
	/**
	 * 类的绑定
	 * @param string $key 要绑定的类
	 * @param string/array/object $value 值
	 * @author wave
	 */
	public static function bind($key,$value){
		if(!isset($key) || !isset($value)){
			throw new XiaoBoException("参数不存在");
		}
		if(!isset(self::$bind[$key]) && $value != ''){
			self::$bind[$key] = $value;
		}
	}
	
	/**
	 * 初始化绑定参数
	 * @param string $key 要初始化·绑定的类
	 * @param array $params 要传入的参数
	 * @return object
	 * @author wave
	 */
	public static function make($key,$params = array()){
		if(!isset(self::$bind[$key])){
			throw new XiaoBoException($key."未进行绑定,请先bind");
		}
		if(isset(self::$app[self::$bindPrefix.$key])){
			return self::$app[self::$bindPrefix.$key];
		}
		
		$bool = class_exists($key) ? Ref::classInstace($key) : true;
		$bindValBool = is_string(self::$bind[$key]) && class_exists(self::$bind[$key]) ?  
				Ref::classInstace(self::$bind[$key]) : true;

		//判断绑定的KEY的值是当前的KEY的值的实例
		if(self::$bind[$key] instanceof $key && !$bool){
			self::$app[self::$bindPrefix.$key] = self::$bind[$key];
		}
		//判断绑定的KEY的值不是当前的KEY的值的实例
		 if(!self::$bind[$key] instanceof $key && !$bool && !$bindValBool){
			self::$app[self::$bindPrefix.$key] = Ref::instance(self::$bind[$key]);
		}
		//判断绑定的KEY的值是数组
		 if(is_array(self::$bind[$key]) && !$bool){
			self::$app[self::$bindPrefix.$key] = Ref::instance(self::$bind[$key]);
		}
		//判断绑定的KEY的值是闭包函数 或者是回调函数
		 if(self::$bind[$key] instanceof Closure || is_callable(self::$bind[$key])){
			self::$app[self::$bindPrefix.$key] = is_array($params) ? 
					call_user_func_array(self::$bind[$key],$params) : 
			 		call_user_func(self::$bind[$key],$params);
		}
		
		//判断返回值不是当前类的实例,并且当前KEY是类
		if(isset(self::$app[self::$bindPrefix.$key]) && 
		 	!self::$app[self::$bindPrefix.$key] instanceof $key && !$bool) 
		{
			self::$app[self::$bindPrefix.$key] = !empty(self::$app[self::$bindPrefix.$key]) ? 
						Ref::instance(self::$app[self::$bindPrefix.$key]) :
			 			Ref::instance(self::$bind[$key]);
		}

		//判断当前key的值不是类
		 if($bool && $bindValBool && empty(self::$app[self::$bindPrefix.$key])){
			self::$app[self::$bindPrefix.$key] = self::$bind[$key];
		}
		//判断当前绑定key的值是类
		 if(!$bindValBool){
			self::$app[self::$bindPrefix.$key] = Ref::instance($params);
		}
		return self::get($key,true);
	}
	
	
	/**
	 * 获取值	
	 * @param string $key 要绑定的key
	 * @param bool $bind 是否获取绑定值
	 * @return null/bool
	 * @author wave
	 */
	public static function get($key,$bind = false){
		if(isset(self::$app[self::$bindPrefix.$key]) && $bind ){
			return self::$app[self::$bindPrefix.$key];
		}else if(isset(self::$app[$key]) && !$bind){
			return self::$app[$key];
		}
		return NULL;
	}

	/**
	 * 删除值	
	 * @param string $key 要删除的key
	 * @param bool $bind 是否删除绑定值
	 * @author wave
	 */
	public static function del($key,$bind = false){
		if(isset(self::$bind[$key]) && $bind ){
			unset(self::$app[self::$bindPrefix.$key]);
			unset(self::$app[self::$bbindPrefix.$key]);
			unset(self::$bind[$key]);
		}else if(isset(self::$app[$key]) && !$bind ){
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
