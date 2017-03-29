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
	 * 加载模型文件
	 * @param string $modelFile 模型文件 不带Model.php
	 * @param array $params 参数
	 * @param string $func 方法名
	 * @return object
	 * @author wave
	 */
	public function importModel($modelFile = '',$params=array(),$func=''){
		LoadModel::import($modelFile.MOD_SUFFOIX,ModelApi::getModelPath());
		return LoadModel::Load($modelFile,$params,$func);	
	}


	/**
	 * 初始化视图Api
	 * @author wave
	 */
	public function View() {
		return Container::get('View');
	}

	/**
	 * 初始化模型Api
	 * @author wave
	 */
	public function ModelType($type = 'Mysql') {
		Container::set('ModelApi_init',"ModelApi::init",array($type));
		return Container::get('ModelApi_init');
	}

	/**
	 * 加载模型
	 * @param String $tableName 表名
	 * @param Array  $conntion 连接数据库
	 * @return OBject
	 * @author wave
	 */
	public function LoadModel($tableName = '',$conntion = array()) {
		return ModelApi::LoadModel($tableName,$conntion);
	}

	/**
	 * 导入文件
	 * @param String $file  文件名
	 * @param String $path 文件路径
	 * @param bool $first 是否首字母转换大些
	 * @author wave
	 */
	public function import($file = '',$path = '',$first = false){
		return LoadModel::import($file,$this->getPath().$path,$first);
	}

	/**
	 * url专跳
	 * @param Array $params 参数
	 * @param bool $urlRewrite 是否伪静态
	 * @author wave
	 */
	public function urlTo($params = array(), $urlRewrite = false){
		return urlTo($params,$urlRewrite);
	}
	
 	/**
 	 * 获取当前url参数
 	 * @return string
 	 * @author wave
 	 */
	public function getUrl(){
		return RouteApi::getUrl();
	}

	/**
 	 * 获取当前url分组
 	 * @return string
 	 * @author wave
 	 */
 	 public function getGroup(){
 		return RouteApi::getGroup();
 	}

 	/**
 	 * 获取当前url类名
 	 * @return string
 	 * @author wave
 	 */
 	public function getClass(){
 		return RouteApi::getClass();
 	}

 	/**
 	 * 获取当前url方法名
 	 * @return string
 	 * @author wave
 	 */
  	public function getAction(){
 		return RouteApi::getAction();
 	}

 	/**
 	 * 获取当前url域名
 	 * @return string
 	 * @author wave
 	 */
  	public function getHost(){
 		return RouteApi::getHost();
 	}

 	 /**
 	 * 获取当前scheme
 	 * @return string
 	 * @author wave
 	 */
  	public function getScheme(){
 		return RouteApi::getScheme();
 	}


 	/**
 	 * 获取网站跟目录
 	 * @author wave
 	 */
 	public function getPath(){
 		return getcwd().ROUTE_DS;
 	}


	/**
	 * 加载数据库配置
	 * @return OBject
	 * @author wave
	 */
	private function config(){
		return ModelApi::config();
	}



}

?>
