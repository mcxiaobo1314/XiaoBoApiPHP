<?php
/**
 * 选择模型加载类
 * @author wave
 */

if(!defined('MODEL_TOKEN')) {
	header("HTTP/1.1 404 not found");
	exit('404 not found');
}

class LoadModel {

	/**
	 * 选择模型加载
	 * @param String $dao  数据库类型
	 * @param Array $params 参数
	 * @param String $func 回调方法
	 * @param string $tableName 表名
	 * @return  OBJECT
	 * @author wave
	 */
	static function load($dao = 'Mysql',$params = array(),$func = '',$tableName = '') {
		static $objArr = array();
		$dao = ucfirst($dao);
		if(!class_exists($dao)) {
			throw new XiaoBoException("加载模型类名:".$dao."不存在");
		}
		//初始化反射类
		Ref::classInstace($dao);

		if(!Ref::hasMethod($func)){
			throw new XiaoBoException("模型方法".$func."不存在");
		}

		if(empty($objArr[$tableName.$dao])) {
			//初始化类
			$objArr[$tableName.$dao] = Ref::instance();
			//初始化反射类方法
			Ref::methodInstace($dao,$func);
			//传入参数
			Ref::invokeArgs($params,$objArr[$tableName.$dao]);
		}
		// if(method_exists($objArr[$tableName.$dao], $func)){
		// 	 call_user_func_array(array($objArr[$tableName.$dao],$func),$params);
		// }
		return $objArr[$tableName.$dao];
	}

	/**
	 * 导入文件
	 * @param String $file  文件名
	 * @param String $path 文件路径
	 * @param bool $first 是否首字母转换大些
	 * @author wave
	 */
	static function import($file = '', $path = '',$first = true) {
		static $pathArr = array();
		$file = $first === true  ?  ucfirst($file) : $file;
		$path = $path.$file;
		if(empty($pathArr[$path])) {
			$pathArr[$path] = $path;
		}
		if(file_exists($pathArr[$path])) {
			require $pathArr[$path];
		}
	}

}

// 调用示例
/*
class AbModel  {
	public function init($a,$b){ 
		//echo $a.'---'.$b;
	}

	public function aa(){
		echo 'aaa';
	}
}
$a = LoadModel::load('ab',array(1,2),'init');
$a->aa();
*/
