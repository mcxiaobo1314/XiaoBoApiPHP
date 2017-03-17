<?php
/**
 * 路由加载控制器
 * @author wave
 */

class Route {

	/**
	 * 保存控制器路径
	 * @author wave
	 */
	public $controllerPath = '';

	/**
	 * 分组名字
	 */
	public $groupName = "";

	/**
	 * 类名字
	 * @author wave
	 */
	public $className = '';

	/**
	 * 方法名字
	 * @author wave
	 */
	public $actionName = '';

	/**
	 * 保存URL参数的数组
	 * @author wave
	 */
	public $getUrlParamArr = array();

	/**
	 * 获取请求协议是http/https
	 * @author wave
	 */
	public $scheme = 'http';

	/**
	 * 获取当前网站域名
	 * @author wave
	 */
	public $host = '';

	/**
	 * 标示定义路由
	 * @author wave
	 */
	public $flag = false;


	public $get  = array();



	/**
	 * 初始化URL参数
	 * @author wave
	 */
	public function coustructs() {
		$this->controllerPath = $this->getPath().APP_ROOT_PATH;
		$this->getUrlParamArr =	$this->expUrlParamArr($this->getUrlParam());
		if(count($this->getUrlParamArr) == 1) {
			$this->getUrlParamArr = $this->expUrlParamArr($this->getUrlParam());
		}
		XiaoBoError::init($this->getUrlParamArr);
		//$this->init();
	}


	/**
	 * 设置路由
	 * @param string $groupName  分组
	 * @param string $className  类名
	 * @param string $actionName 方法名
	 * @param Array  $params  参数
	 * @author wave
	 */
	public function setRoute($groupName, $className,$actionName,$params = array()){
			if($groupName && $className && $actionName){
				$this->getUrlParamArr[0] = $groupName;
				$this->getUrlParamArr[1] = $className;
				$this->getUrlParamArr[2] = $actionName;
				$this->getUrlParamArr = array_merge($this->getUrlParamArr,$params);
				$this->flag = true;
			}

	}


	/**
	 * 拆分URL为数组
	 * @author wave
	 */
	protected function expUrlParamArr($dataStr = '' , $exp = ROUTE_DS) {
		$dataStr = str_replace(array('//'), array($exp) , $dataStr);
		$getUrlParamArr = explode($exp, $dataStr);
		$getUrlParamArr = $this->filterArr($getUrlParamArr);
		return !empty($getUrlParamArr) ? $getUrlParamArr : false;
	}


	/**
	 * 判断文件路径是否存在
	 * @return boolen or String
	 * @author wave
	 */
	protected function isPath() {
		$controllerPath =  $this->controllerPath . ROUTE_DS . $this->getUrlParamArr[0].ROUTE_DS;
		$this->groupName = $this->getUrlParamArr[0];

		//判断不是目录文件
		if( !file_exists($controllerPath)) {
			throw new XiaoBoException($this->groupName."分组文件不存在");
		} 
		
		return $this->groupName;
	}

	/**
	 * 判断控制器文件是否存在
	 * @return boolen or String
	 * @author wave
	 */
	protected function isController() {
		$defaultPath = $this->isPath();
		$defaultFile = ($this->isPath() !== false && count($this->getUrlParamArr) >=3) ? 
						$this->getUrlParamArr[1] : $this->getUrlParamArr[0];

		$controllerPath =  $this->controllerPath . ROUTE_DS . 
				$defaultPath . ROUTE_DS . 
				CONTROLLER . ROUTE_DS . 
				$defaultFile . 
				CON_SUFFOIX;
		$this->className = $defaultFile;
		//判断是否是控制器文件
		if( !file_exists($controllerPath)) {
			return false;
			
		} 
		
		return $controllerPath;
	}



	/**
	 * 初始化类
	 * @author wave
	 */
	public function init() {
		$this->coustructs();
		$actionName = ($this->isPath() !== false && isset($this->getUrlParamArr[2])) ? 
				$this->getUrlParamArr[2] : $this->getUrlParamArr[1];
		$className = $this->isClass();
		($this->isPath() !== false)  ? 
		array_splice($this->getUrlParamArr,0,3) : 
		array_splice($this->getUrlParamArr,0,2); 
		$this->actionName = $actionName;
		if ( !empty($className) ) {
			//反射类初始化
			Ref::classInstace($className);
		}

		if($this->isAction($actionName)){
			$this->getUrlParamArr = !empty($this->getUrlParamArr) ? $this->getUrlParamArr : array();
			//视图初始化
			if(class_exists('ViewApi')){
				Ref::methodInstace('View','init');
				Ref::invokeArgs(array(array(
					'group' => $this->groupName,
					'class' => $this->className,
					'action' => $this->actionName,
					'controllerPath' => $this->controllerPath
				)),ViewApi::$view);
			}
			//初始化反射类方法
			Ref::methodInstace($className,$actionName);
			(BINDURLPARAM === true) && $this->getUrlParamArr = $this->bindParam();
			return Ref::invokeArgs($this->getUrlParamArr);	
		}
	}


	/**
	 * 对函数进行绑定参数
	 * @return Array
	 * @author wave
	 */
	public function bindParam(){
		$bindParam = array();
		$urlParam = $this->getUrlParam($this->flag);
		$bindParam = Ref::getParams();
		$diffArr = array($this->groupName,$this->className,$this->actionName);
		$flag = false;
		if(empty($this->get)){
			$urlParam = $this->expUrlParamArr($urlParam);
			$urlParam = array_diff($urlParam,$diffArr);
			$urlParam = $this->filterArr($urlParam);
			$temp = array();
			foreach($urlParam as $key=>$value){
				if($key % 2 === 0 && in_array($value, $bindParam) && isset($bindParam[$value]) && !empty($urlParam[$key + 1])) {
					$bindParam[$value] = $urlParam[$key + 1];
					$flag = true;
					$temp[$value] = $value;
				}
			}
			$bindParam = array_intersect_key($bindParam, $temp);
		}else{
			parse_str($this->get,$this->get);
			$this->get = array_diff($this->get,$diffArr);
			foreach($bindParam as $value){
				if(isset($this->get[$value]) && isset($bindParam[$value]) && !empty($this->get[$value])){
					$bindParam[$value] = $this->get[$value];
					$flag = true;
				}else{
					unset($bindParam[$value]);
				}

			}
		}

		return ($flag === true) ? $bindParam : array();
	}

	/**
	 * 判断类的方法是否存在
	 * @return boolen
	 * @author wave
	 */
	protected function isAction($actionName) {
		if( !Ref::hasMethod($actionName)){
			throw new XiaoBoException($actionName.'方法不存在');
		}
		return true;
	}

	/**
	 * 判断类是否存在
	 * @return String
	 * @author wave
	 */
	protected function isClass() {
		$controllerPath = $this->isController();
		$this->load($controllerPath);
		$controllerClass = rtrim($this->className.CON_SUFFOIX,'.php') ;
		if(!class_exists($controllerClass) ) {
			throw new XiaoBoException($this->className.'控制器不存在');
		} 
		return $controllerClass;
	}

	/**
	 * 获取设置默认url
	 * @return string
	 * @author wave
	 */
	protected function getDefualtUrl(){
		$url = DEFAULT_ROUTE;
		if(empty($_GET)){
			$urlArr = $this->filterArr(explode('/', $url));
			$_GET[G] = $urlArr[0];
			$_GET[C] = $urlArr[1];
			$_GET[A] = $urlArr[2];
		}
		
		return $url;
	}



	/**
	 * 获取url参数
	 * @param bool $flag true 是获取默认url参数，false是获取别名url参数
	 * @return String
	 * @author wave
	 */
	public  function getUrlParam($flag = true) {
		$getParam = false;
		$rootPath = ROUTE_DS.basename($this->getPath()).ROUTE_DS;
		if ( !empty($_SERVER['ORIG_PATH_INFO']) ) {
			$url = $_SERVER['ORIG_PATH_INFO'];
			$urlNum =2;  //伪静态
		} else if ( !empty($_SERVER['PATH_INFO']) ) {
			$url = $_SERVER['PATH_INFO'];
			$urlNum =2; //伪静态
		} else if ( !empty($_SERVER['REQUEST_URI']) ) {
			$url = $_SERVER['REQUEST_URI'];
			$url = $this->substr($url, '','index.php');
			$urlArr = parse_url($url);

			if(isset($urlArr['query'])){
				$this->get = $urlArr['query'];
				$getParam = $this->ReturnGetParam($urlArr['query']);
			}
			$urlNum = 3; //动态
		} else if (!empty($_SERVER['argv'][1])){  //cli 模式
			$url = $_SERVER['argv'][1];
		}

		$this->setHost();
		$this->setScheme();

		if($getParam !== false  && $flag && $urlNum === 3) {
			$url = $getParam;
		}


		if($url === $rootPath || $url === ROUTE_DS || (empty($url) && $getParam === false && $urlNum ===3)) {
			$this->get = array();
			$url = $this->getDefualtUrl();
		}
		if($this->flag && $flag){
			$url = implode('/', $this->getUrlParamArr);
		}
		return $url;
	}

	/**
	 * 设置scheme
	 * @author wave
	 */
	protected function setScheme(){
		$this->scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : $this->host; 
	}


	/**
	 * 设置host
	 * @author wave
	 */
	protected function setHost(){
		$this->host = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $this->host; 
	}

	/**
	 * 返回GET参入参数
	 * @param string $getStr 获取get参数
	 * @author wave
	 */
	protected function ReturnGetParam($getStr) {	
		parse_str($getStr,$get);
		if( isset($get[C]) && isset($get[A]) ) {
			$getUrl = ROUTE_DS . $get[C] . ROUTE_DS . $get[A] . ROUTE_DS;
			unset($get[C]);
			unset($get[A]);
		}

		if(isset($get[G]) ) {
			$getUrl =  ROUTE_DS . $get[G] . (empty($getUrl) ? ROUTE_DS : $getUrl);
			unset($get[G]);
		}
		if(!empty($get)){
			$getUrl .= implode('/', $get);
		}

		return empty($getUrl) ? false : $getUrl;
	}

	/**
	 * 获取URL参数
	 * @return Array
	 * @author wave
	 */
	protected function getParam() {
		$get = !empty($_GET) ? $_GET : array();
		if( isset($get[C]) && isset($get[A]) ) {
			unset($get[C]);
			unset($get[A]);
		}
		if( isset($get[G]) ) {
			unset($get[G]);
		}
		return $get;
	}



	/**
	 * 获取服务器相对路径目录
	 * @return String 
	 * @author wave
	 */
	protected function getPath() {
		$appPath = dirname(dirname(__FILE__));
		$currPath = basename($appPath);
		$searchArr = array('\\',$currPath);
		$replaceArr = array(ROUTE_DS,'');
		$appPath = str_replace($searchArr, $replaceArr, $appPath);
		return $appPath;
	}

	/**
	 * 引入文件
	 * @param Sting $filePath 文件相对路径
	 * @author wave
	 */
	protected function load($filePath = '') {
		$filePath  = str_replace('//', '/', $filePath);
		static $fileArr = array();
		if( empty($fileArr[$filePath]) && file_exists($filePath) ) {
			$fileArr[$filePath] = $filePath;
		}

		if( !empty($fileArr[$filePath]) ) {
			require $fileArr[$filePath];
		}	
	}

	/**
	 * 过滤空的数组
	 * @param Array $arr 要过滤的空数组
	 * @return Array 
	 * @author wave
	 */
	protected function filterArr($arr){
		if ( !empty($arr) ) {
			return	array_values(array_filter($arr));
		}
		return array();
	}

	/**
	 * 替换第一次出现的字符串
	 * @param string $string 要替换的字符串
	 * @param string $repalce 被替换的字符串
	 * @param string $t_repalce 要替换的字符串
	 * @return string
	 * @author wave
	 */
	protected function substr($string,$repalce,$t_repalce){
		if(strpos($string,$t_repalce)  !== false){
			return substr_replace($string,$repalce,strpos($string,$t_repalce),strlen($t_repalce));
		}
		return $string;
	}


}
