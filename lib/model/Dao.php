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
	 * 数据
	 * @author wave
	 */
	public $data = array();

	/**
	 * 记录单条SQL语句
	 * @author wave
	 */
	public $firstSql = '';

	/**
	 * 保存检验参数数组
	 * @author wave
	 */
	public $validate = array();
	
	/**
	 * 字段
	 * @author wave
	 */
	public $fields = '';

	/**
	 * 联表字段
	 * @author wave
	 */
	public $joinFields = '';

	/**
	 * 条件
	 * @author wave
	 */
	public $where = '';

	/**
	 * 联表
	 * @author wave
	 */
	public $join = '';

	/**
	 * 限制
	 * @author wave
	 */
	public $limit = '';
	
	/**
	 * 分组
	 * @author wave
	 */
	public $group = '';

	/**
	 * 排序
	 * @author wave
	 */
	public $order = '';

	/**
	 * 分组条件
	 * @author wave
	 */
	public $having = '';


	/**
	 * 表前缀
	 * @author wave
	 */
	public $tablePrefix = '';

	/**
	 * 参数校验错误
	 * @author wave
	 */
	public $validateErr = '';

	/**
	 * 数据库连接
	 * @author wave
	 */
	protected $db = '';

	/**
	 * 表名
	 * @author wave
	 */
	protected $dbTableName = '';
	
	/**
	 * 执行方法
	 * @author wave
	 */
	protected $motehd = '';

	/**
	 *  默认连接
	 * @author wave
	 */
	protected $defaultCon = array();

	/**
	 * 拼接sql语句字段数组
	 * @author wave
	 */
	protected $sqlFiledArr = array(
		0 => 'select',
		1 => 'insert into',
		2 => 'update',
		3 => 'delete',
		4 => 'from',
		5 => 'where',
		6 => 'group by',
		7 => 'order by',
		8 => 'join',
		9 => 'limit',
		10 => 'having',
		11 => 'set',
		12 => 'values',
		13 => 'on',
		14 => '(',
		15 => ')'
	);

	/**
	 * 参数校验的key
	 * @author wave
	 */
	protected $_validateKeyArr = array(
		'name',
		'reg',
		'error'
	);

	/**
	 * 表字段结构
	 * @author wave
	 */
	static protected $TableFieldStruct = array();


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
	 * 对参数的校验
	 * @return bool
	 * @author wave
	 */
	public function validate(){
		if(is_array($this->validate)){
			foreach ($this->validate as $key => $value) {
				if($this->diffArr($value,$this->_validateKeyArr)){
					$this->validateErr = preg_match($value['reg'], $this->data[$value['name']]) 
								? true : $value['error'];
				}
				if($this->validateErr)  return false;
			}
			return true;
		}
		return false;
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
