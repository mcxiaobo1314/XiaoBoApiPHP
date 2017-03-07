<?php
/**
 * 数据库连接类
 * @author wave
 */

if(!defined('MODEL_TOKEN')) {
	header("HTTP/1.1 404 not found");
	exit('404 not found');
}

class DbConnect {

	public $db = '';

	/**
	 * 数据库地址
	 * @author wave
	 */
	protected $host;
	
	/**
	 * 数据库名字
	 * @author wave
	 */
	protected $dbname;
	
	/**
	 * 数据库类型
	 * @author wave
	 */
	protected $type;

	/**
	 * 数据库端口
	 * @author wave
	 */
	protected $port;

	/**
	 * 数据库帐号
	 * @author wave
	 */
	protected $user;

	/**
	 * 数据库密码
	 * @author wave
	 */
	protected $pwd;

	/**
	 * 数据库编码
	 * @author wave
	 */
	protected $charset;

	/**
	 * 初始化
	 * @param Srting $host 数据库地址
	 * @param Srting $dbname 数据库名字
	 * @param Srting $type 数据库类型
	 * @param Srting $port 数据库端口
	 * @param Srting $charset 数据库编码
	 * @param Srting $user 数据库帐号
	 * @param Srting $pwd 数据库密码
	 * @author wave
	 */
	public function init($host = 'localhost', $dbname = '', $user = 'root', $pwd = '', $charset = 'utf8', $type = 'Mysql', $port = '3306') {
		$this->host = $host;
		$this->dbname = $dbname;
		$this->type = $type;
		$this->port = $port;
		$this->user = $user;
		$this->pwd = $pwd;
		$this->charset = $charset;
		if(class_exists('PDO')) {
			switch ($type) {
				case 'mysql':
				case 'Mysql':
					$this->db = $this->mysqlPdo();
				break;
				case 'sqlite':
				case 'Sqlite':
					$this->db = $this->sqlitePdo();
				break;
				default:
					throw new XiaoBoException("该".$type."类不存在");
					break;
			}

			
		}
	}

	/**
	 * 初始化sqlitePdo
	 * @return object
	 * @author wave
	 */
	protected function sqlitePdo() {
		try{
			$pdo = new PDO(
				$this->type . 
				':' . $this->dbname,
				$this->user,
				$this->pwd
			);

			$pdo->exec('set names '.$this->charset.';'); 
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
		}catch(PDOException $e){
			throw new XiaoBoException('数据库连接失败:'.$e->getMessage());
		}

		
		return $pdo;
	}


	/**
	 * 初始化mysqlPDO
	 * @return object
	 * @author wave
	 */
	protected function mysqlPdo() {
		try{
			$pdo = new PDO(
				$this->type . 
				':host=' . $this->host . 
				';port=' . $this->port . 
				';dbname=' . $this->dbname , 
				$this->user , 
				$this->pwd , 
				array(
			    	PDO::ATTR_PERSISTENT => true
				)
			);
			$pdo->query('set names '.$this->charset.';'); 
			$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
		}catch(PDOException $e){
			throw new XiaoBoException('数据库连接失败:'.$e->getMessage());
		}

		
		return $pdo;
	}

}