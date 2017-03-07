<?php
/**
 * mysql数据库操作类
 * @author wave
 */

if(!defined('MODEL_TOKEN')) {
	header("HTTP/1.1 404 not found");
	exit('404 not found');
}

LoadModel::import('/Dao.php',dirname(__FILE__));
LoadModel::import('/DbConnect.php',dirname(__FILE__));

class Mysql extends Dao {
	
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
	 * 记录单条SQL语句
	 * @author wave
	 */
	public $firstSql = '';

	/**
	 * 数据
	 * @author wave
	 */
	public $data = array();

	/**
	 * 数据库连接
	 * @author wave
	 */
	public $db = '';

	/**
	 * 表名
	 * @author wave
	 */
	protected $dbTableName = '';

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
	 * 表字段结构
	 * @author wave
	 */
	static protected $TableFieldStruct = array();

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
		$this->showTableFileds();
	}

	/**
	 * 插入
	 * @return number
	 * @author wave
	 */
	public function insert() {
		if(empty($this->data)) {
			return false;
		}
		if( empty( $this->fields ) ) {
			$this->fields = $this->getFields();
		}
		$this->firstSql = $this->sqlFiledArr[1] . ' ' .
			self::BACK . $this->dbTableName . self::BACK . ' ' .
			$this->sqlFiledArr[14] . ' ' .
			$this->fields . ' ' .
			$this->sqlFiledArr[15] . ' ' .
			$this->sqlFiledArr[12] . ' ' .
			$this->sqlFiledArr[14] . ' ' .
			'"'. implode('","', $this->data) .'" ' .
			$this->sqlFiledArr[15];
		$this->_unset();
		return $this->query($this->firstSql);
	}

	/**
	 * 更新
	 * @return number
	 * @author wave
	 */
	public function save() {
		if(empty($this->data)) {
			return false;
		}
		$data = $this->data;
		$this->data = '';
		foreach( $data as $key => $value ) {
			$this->data .= $key . 
				self::DEFAULTJUDGE . '"' . 
				$value . '",'; 
		}
		unset($data);
		$this->data = rtrim($this->data,',');
		$this->firstSql = $this->sqlFiledArr[2] . ' ' .
			self::BACK . $this->dbTableName . self::BACK . ' ' .
			$this->sqlFiledArr[11] . ' ' .
			$this->data . ' ' .
			$this->where;
		$this->_unset();
		return $this->query($this->firstSql);
	}

	/**
	 * 批量更新
	 * @return number
	 * @author wave
	 */
	public function saveAll() {

	}

	/**
	 * 删除
	 * @return number
	 * @author wave
	 */
	public function del() {
		$this->firstSql = $this->sqlFiledArr[3] . ' ' .
			$this->sqlFiledArr[4] . ' ' .
			self::BACK . $this->dbTableName . self::BACK . ' ' .
			$this->where;
		$this->_unset();
		return $this->query($this->firstSql);
	}

	/**
	 * 查询
	 * @return Array
	 * @author wave
	 */
	public function find() {
		if( empty( $this->fields ) ) {
			$this->fields = $this->getFields();
			$this->fields .= ',' . $this->joinFields;
		}
		$this->fields = rtrim($this->fields , ',');
		$this->firstSql = $this->sqlFiledArr[0] .' ' .
			$this->fields . ' ' .
			$this->sqlFiledArr[4] . ' ' .
			self::BACK . $this->dbTableName . self::BACK . ' ' .
			$this->join . ' ' .
			$this->where . ' ' .
			$this->group . ' ' .
			$this->having . ' ' .
			$this->order . ' ' .
			$this->limit . ' ' ;
		$this->_unset();
		return $this->query($this->firstSql);
	}

	/**
	 * 计算行数
	 * @return object
	 * @author wave
	 */
	public function count() {
		$args = func_get_args();
		$args = isset($args[0]) ? $args[0] : '';
		$this->fields = 'count(*)';
		if(!empty($args)){
			$this->fields = 'count('.$args.')';
		}
		return $this->find();
	}

	/**
	 * 查询一行数据
	 * @return object
	 * @author wave
	 */
	public function first() {
		$this->limit .= $this->sqlFiledArr[9] . ' 1';
		return $this->find();
	}

	/**
	 * 分组
	 * @return object
	 * @author wave
	 */
	public function group() {
		$args = func_get_args();
		if(!empty($args[0])) {
			$this->group .= $this->sqlFiledArr[6] . ' ' . $args[0];
		}
		return $this;
	}

	/**
	 * 排序
	 * @return object
	 * @author wave
	 */
	public function order() {
		$args = func_get_args();
		if(!empty($args[0]) && !empty($args[1]) && in_array($args[1],array('desc','asc')) ) {
			$this->order .= $this->sqlFiledArr[7] . 
				' ' . $args[0] . 
				' ' . $args[1];
		}
		return $this;
	}

	/**
	 * 分组条件
	 * @return object
	 * @author wave
	 */
	public function having() {
		$args = func_get_args();
		if(!empty($args[0]) ) {
			$this->having .= $this->sqlFiledArr[10] . 
				' ' . $args[0];
		}
		return $this;
	}

	/**
	 * 限制
	 * @return object
	 * @author wave
	 */
	public function limit() {
		$args = func_get_args();
		$this->limit = $this->sqlFiledArr[9] . ' ';
		if(!empty($args[0])) {
			$this->limit .= $args[0];
		}
		if(!empty($args[1])) {
			$this->limit .= ',' . $args[1];
		}
		return $this;
	}

	/**
	 * 联表
	 * @return object
	 * @author wave
	 */
	public function join() {
		$args = func_get_args();
		if(empty($args[0]) || empty($args[1]) || empty($args[2]) ) {
			return $this;
		}
		if( !in_array($args[0], array('left','right','inner') ) ){
			throw new XiaoBoException("语法不正确");
			
		}
		$this->showTableFileds($args[1]);
		$this->joinFields .=  $this->getFields($args[1]).',';
		if(is_string($args[0]) && is_string($args[1]) && is_string($args[2])) {
			$this->join .= ' '.$args[0]. ' ' . 
				$this->sqlFiledArr[8] .' '. 
				self::BACK .$args[1] . 
				self::BACK. ' ' . 
				$this->sqlFiledArr[13] . ' ' . 
				$args[2];
		}
		return $this;
	}

	/**
	 * 条件
	 * @return object
	 * @author wave
	 */
	public function where() {
		$args = func_get_args();
		$args = isset($args[0]) ? $args[0] : '';
		if( !empty($args) && is_string($args) ) {
			$this->where .= $this->sqlFiledArr[5] . 
				' ' . $args;
		}
		return $this;
	}

	/**
	 * 设置字段
	 * @return object
	 * @author wave
	 */
	public function fields() {
		$args = func_get_args();
		$args = isset($args[0]) ? $args[0] : '';
		if(!empty($args)) {
			$this->fields = is_array($args) ? implode(',', $args) : $args;
		}
		return $this;
	}
	
	/**
	 * 执行sql语句
	 * @return Array or Number
	 * @author wave
	 */
	public function query() {
		$args = func_get_args();
		$args = isset($args[0]) ? $args[0] : '';
		if(empty($args)) {
			return false;
		}
		try{
			if(strpos($args, 'select') !== false || strpos($args, 'show') !== false) {
				$result = $this->db->query($args);
				$result->setFetchMode(PDO::FETCH_ASSOC);
				return $result->fetchAll();
			}else {
				return $this->db->exec($args);
			}
		}catch(PDOException $e){
			throw new XiaoBoException("语法错误:".$e->getMessage());
		}
		
	}

	/**
	 * 获取主键
	 * @return String
	 * @author wave
	 */
	public function getPK() {
		foreach (self::$TableFieldStruct[$this->dbTableName] as $key => $value) {
			if($value['Key'] == 'PRI') {
				return $value['Field'];
			}
		}
	}

	/**
	 * 预处理语句
	 * @param string $sql 语句
	 * @return object
	 * @author wave
	 */
	public function prepare($sql){
		return $this->db->prepare($sql);
	}


	/**
	 * 获取表的字段
	 * @author wave
	 */
	protected function getFields() {
		$args = func_get_args();
		$args = isset($args[0]) ? $args[0] : '';
		$name = $this->dbTableName;
		$fields = '';
		if(!empty($args)) {
			$name = $args;
		}
		foreach (self::$TableFieldStruct[$name] as $key => $value) {
			$fields .= self::BACK . $name . 
					self::BACK . '.' . 
					self::BACK . $value['Field'] . 
					self::BACK . ',';
		}
		return rtrim($fields,',');
	}


	/**
	 * 毁掉变量值
	 * @author wave
	 */
	protected function _unset() {
		$this->fields = '';
		$this->where = '';
		$this->join = '';
		$this->limit = '';
		$this->group = '';
		$this->order = '';
		$this->having = '';
		$this->joinFields = '';
		$this->data = array();
	}

	/**
	 * 获取表字段结构
	 * @author wave
	 */
	protected function showTableFileds(){
		$args = func_get_args();
		$args = isset($args[0]) ? $args[0] : '';
		$name = $this->dbTableName;
		if(!empty($args)) {
			$name = $args;
		}

		if(!empty($name)  &&  empty(self::$TableFieldStruct[$name])){
			self::$TableFieldStruct[$name] = $this->query('show columns from '. $name);
		}
	}

}

