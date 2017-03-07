<?php

/**
 * Sqlite3数据库操作类
 * @author wave
 */


if(!defined('MODEL_TOKEN')) {
	header("HTTP/1.1 404 not found");
	exit('404 not found');
}
LoadModel::import('/Dao.php',dirname(__FILE__));
LoadModel::import('/DbConnect.php',dirname(__FILE__));

class Sqlite extends Dao{

	public $dbTableName = '';

	protected $db = '';

	protected $defaultCon = array();

	/**
	 * 初始化函数
	 * @author wave
	 */
	public function init() {
		 $args = func_get_args();
		 $defaultCon = array();

		if(!empty($args[0])  && is_string($args[0]) ) {
			$this->dbTableName =  $args[0]; //表名
		}

		if(!empty($args[0])  && is_array($args[0]) && $this->diffArr($args[0])) {
			$this->defaultCon = $args[0]; //连接数据库配置
		}

		if(!empty($args[1])  && is_array($args[1]) && $this->diffArr($args[1])) {
			$this->defaultCon = $args[1]; //连接数据库配置
		}
		
		$this->defaultCon = (is_array($this->defaultCon) && !empty($this->defaultCon)) ? $this->defaultCon : '';
		$this->db = parent::init($this->defaultCon);
	}


	/**
	 * 查询方法
	 * @author wave	
	 */
	public  function find(){


	}

	/**
	 * 查询总行数
	 * @author wave	
	 */
	public function count(){

	}

	/**
	 * 查询一条数据
	 * @author wave	
	 */
	public function first(){

	}

	/**
	 * 新增方法
	 * @author wave	
	 */
	public function insert(){

	}

	/**
	 * 更新方法
	 * @author wave	
	 */
	public function save(){

	}

	/**
	 * 更新所有方法
	 * @author wave	
	 */
	public function saveAll(){

	}

	/**
	 * 删除方法
	 * @author wave	
	 */
	public function del(){

	}

	/**
	 * 执行sql
	 * @author wave
	 */
	public function query(){
		$args = func_get_args();
		$args = isset($args[0]) ? $args[0] : '';
		if(empty($args)) {
			return false;
		}
		try{
			if(strpos($args, 'select') !== false) {
				$result = $this->db->prepare($args);
				$result->execute();
				$result->setFetchMode(PDO::FETCH_ASSOC);
				return $result->fetchAll();
			}else {
				return $this->db->exec($args);
			}
		}catch(PDOException $e){
			throw new XiaoBoException("语法错误:".$e->getMessage());
		}
	}
}