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
	 * @return  OBJECT
	 * @author wave
	 */
	static function load($dao = 'Mysql',$params = array(),$func = '') {
		static $objArr = array();
		$dao = ucfirst($dao);
		if(!class_exists($dao)) {
			throw new XiaoBoException("加载模型类名:".$dao."不存在");
		}
		if(empty($objArr[$dao])) {
			$objArr[$dao] = new $dao;
		}
		if(method_exists($objArr[$dao], $func)){
			 call_user_func_array(array($objArr[$dao],$func),$params);
		}
		return $objArr[$dao];
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