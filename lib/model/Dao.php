<?php
/**
 * 数据库抽象类
 * @author wave	
 */

if(!defined('MODEL_TOKEN')) {
	header("HTTP/1.1 404 not found");
	exit('404 not found');
}

abstract class Dao {

	/**
	 * 条件判断符号
	 * @author wave
	 */
	protected $judge = array('>','<','!=','like','<>','>=','<=');


	/**
	 * 反单引号
	 * @author wave
	 */
	const BACK = '`'; 
	
	/**
	 * 默认条件判断符号
	 * @author wave
	 */
	const DEFAULTJUDGE = ' = ';



	/**
	 * 初始化函数
	 * @param Array $defaultCon 数据库连接配置
	 * @return Object
	 * @author wave
	 */
	public function constructs($defaultCon = array()) {
		if(empty($db) && !empty($defaultCon)) {
			$db = $this->connect($defaultCon)->db;
		}
		if(empty($db)) {
			throw new XiaoBoException('连接数据库失败');
		}
		return $db;
	}


	/**
	 * 分割键
	 * @param String $key
	 * @return String
	 * @author wave
	 */
	public function splitKey($key = '') {
		if(empty($key)) {
			return false;
		}
		if(strpos($key,' ') !== false) {
			$key = trim($key);
			$keyArr = array_filter(explode(' ', $key));
			if(in_array($keyArr[1], $this->judge)) {
				return  self::BACK.$keyArr[0].self::BACK.$keyArr[1];
			}
			return false;
		}
		return self::BACK.$key.self::BACK.self::DEFAULTJUDGE;
	}



	/**
	 * 数组键值顺序对比是否全等
	 * @param Array $dataArr 要对比的数组
	 * @param Array $diffArr 要比对的数据组，可以默认为空,会使用系统自带的数组
	 * @param String $type  类型  key是键 value 是值
	 * @return BOOLEN
	 * @author wave
	 */
	protected function diffArr($dataArr , $diffArr = '' , $type = 'key') {

		if(!in_array($type, array('key','value'))) {
			return false;
		}

		if(empty($diffArr)) {
			$diffArr = array('host', 'dbname', 'user', 'pwd', 'charset', 'type', 'port');
		}

		if($type === 'key') {
			$arr = array_keys($dataArr);
		}

		if($type === 'value') {
			$arr = array_values($dataArr);
		}

		$resultArr = array_intersect($diffArr,$arr);

		if(count($resultArr) === count($diffArr)) {
			return true;
		}
		return false;
	}

	/**
	 * 连接数据库
	 * @param Array $defaultCon 连接数据库配置
	 * @return Object or NULL
	 * @author wave
	 */
	protected function connect($defaultCon = array()){
		if(empty($defaultCon)) {
			return '';
		}
		return LoadModel::load(
			'DbConnect',
			array(
				$defaultCon['host'], 
				$defaultCon['dbname'], 
				$defaultCon['user'], 
				$defaultCon['pwd'], 
				$defaultCon['charset'], 
				$defaultCon['type'], 
				$defaultCon['port']
			),
			'init'
		);
	}


	/**
	 * 查询方法
	 * @author wave	
	 */
	abstract protected function find();

	/**
	 * 查询总行数
	 * @author wave	
	 */
	abstract protected function count();

	/**
	 * 查询一条数据
	 * @author wave	
	 */
	abstract protected function first();

	/**
	 * 新增方法
	 * @author wave	
	 */
	abstract protected function insert();

	/**
	 * 更新方法
	 * @author wave	
	 */
	abstract protected function save();

	/**
	 * 更新所有方法
	 * @author wave	
	 */
	abstract protected function saveAll();

	/**
	 * 删除方法
	 * @author wave	
	 */
	abstract protected function del();

	/**
	 * 执行sql
	 * @author wave
	 */
	abstract protected function query();


}
//调用示例
/*
class a extends Dao {
	public function find(){
		var_dump($this->judge);
	}
	public function count(){}
	public function insert(){}
	public function first(){}
	public function save(){}
	public function saveAll(){}
	public function del(){}
	public function query(){}
}

$a = new a();
echo $a->find();
*/
