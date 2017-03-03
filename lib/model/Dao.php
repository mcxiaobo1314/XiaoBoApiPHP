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
