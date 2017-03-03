<?php
/**
 * 控制器API
 * @author wave
 */

class Controller  {

	/**
	 * 保存视图对象
	 * @author wave
	 */
	public $view = '';

	/**
	 * 数据库类型
	 * @author wave
	 */
	public $dbType = 'Mysql';


	/**
	 * 数据库连接
	 * @author wave
	 */
	public $Config;


	/**
	 * 初始化操作
	 * @author wave
	 */
	public function __construct() {
		$this->view = $this->View();
		$this->Config = $this->config();
		if($this->Config->type){	
			$this->dbType = $this->Config->type;
		}
		$this->ModelType($this->dbType);
	}



	/**
	 * 初始化视图Api
	 * @author wave
	 */
	public function View() {
		if(class_exists('ViewApi')) {
			return ViewApi::$view;
		}
		throw new XiaoBoException('请先载入View Api文件');
	}

	/**
	 * 初始化模型Api
	 * @author wave
	 */
	public function ModelType($type = 'Mysql') {
		if(class_exists('ModelApi')) {
			return ModelApi::init($type);
		}
		throw new XiaoBoException('请先载入Model Api文件');
	}

	/**
	 * 加载模型
	 * @param String $tableName 表名
	 * @param Array  $conntion 连接数据库
	 * @return OBject
	 * @author wave
	 */
	public function LoadModel($tableName = '',$conntion = array()) {
		$arr = array($tableName);
		if(!empty($conntion)) {
			array_push($arr, $conntion);
		}elseif($this->Config->host && $this->Config->dbname && $this->Config->username) {
			array_push($arr,array(
				'host' => $this->Config->host,
				'dbname' => $this->Config->dbname,
				'user' => $this->Config->username,
				'pwd' => $this->Config->password,
				'charset' => $this->Config->charset,
				'type' => $this->Config->type,
				'port' => $this->Config->port
			));
		}
		
		return LoadModel::load($this->dbType,$arr,'init');
	}


	/**
	 * 加载数据库配置
	 * @return OBject
	 * @author wave
	 */
	private function config(){
		static $config = null;
		if(!$config && class_exists("Config")){
			$config = new Config;
		}
		return $config;
	}
}

?>