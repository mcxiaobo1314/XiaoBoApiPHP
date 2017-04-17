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
	public $flag = true;

	/**
	 * 保存get参数
	 * @author wave
	 */
	public $get  = array();

	/**
	 * 设置了别名的url
	 * @author wave
	 */
	public $aliasUrl = array();

	/**
	 * 判断是否默认url访问
	 * @author wave
	 */
	public $default = false;



	/**
	 * 初始化URL参数
	 * @author wave
	 */
	public function coustructs() {
		$this->controllerPath = $this->getPath().APP_ROOT_PATH;
		$this->getUrlParamArr =	$this->expUrlParamArr($this->getUrlParam($this->flag));
		$this->setHost();
		$this->setScheme();
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
				$this->getUrlParamArr[G] = $groupName;
				$this->getUrlParamArr[C] = $className;
				$this->getUrlParamArr[A] = $actionName;
				if(!empty($this->get)){
					$this->get = array_diff($this->get,$this->getUrlParamArr);
					$this->getUrlParamArr = array_merge($this->get,$this->getUrlParamArr);
					$params = array_merge($params,$this->getUrlParamArr);
				}
				$this->getUrlParamArr = array_merge($this->getUrlParamArr,$params);

				$this->flag = false;
			}

	}


	/**
	 * 拆分URL为数组
	 * @author wave
	 */
	protected function expUrlParamArr($dataStr = '' , $exp = ROUTE_DS) {
		$dataStr = str_replace(array('//'), array($exp) , $dataStr);
		$getUrlParamArr = !empty($dataStr) ? explode($exp, $dataStr) : array();
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
			throw new XiaoBoException($this->groupName."分组文件不存在",false);
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
		$currentUrl = $this->groupName.ROUTE_DS.$this->className.ROUTE_DS.$this->actionName;

		if($this->isAliasUrlParam($currentUrl) && IS_AILAS && $this->default === false){
			throw new XiaoBoException("已经定义了别名url,请使用别名url访问",false);
				
		}

		if ( !empty($className) ) {
			//反射类初始化
			Ref::classInstace($className);
		}

		if($this->isAction($actionName)){
			$this->getUrlParamArr = !empty($this->getUrlParamArr) ? $this->getUrlParamArr : array();
			Container::bind('View',array(
					'group' => $this->groupName,
					'class' => $this->className,
					'action' => $this->actionName,
					'controllerPath' => $this->controllerPath
			));
			//初始化反射类方法
			Ref::methodInstace($className,$actionName);
			(BINDURLPARAM === true) && $this->getUrlParamArr = $this->bindParam();
			return Ref::invokeArgs($this->getUrlParamArr);	
		}
	}



	/**
	 * 对函数进行绑定参数
	 * @params $currentUrl 当前URL
	 * @return Array
	 * @author wave
	 */
	public function bindParam($currentUrl = ''){
		$bindParam = array();
		$urlParam = $this->isAliasUrlParam($currentUrl) ? $this->getUrlParam($this->flag) : $this->getUrlParamArr;
		$bindParam = Ref::getParams();
		$diffArr = $this->setDiffArr();
		$flag = false;
		if(empty($this->get)){
			$this->isAliasUrlParam($currentUrl) && $urlParam = $this->expUrlParamArr($urlParam);
			if(!empty($urlParam)) {
				$urlParam = array_diff($urlParam,$diffArr);
				$urlParam = $this->filterArr($urlParam);
				$temp = array();

				foreach($urlParam as $key=>$value){
					$findkey = array_search($value, $urlParam);
					if($key % 2 === 0 && isset($bindParam[$value]) && !empty($urlParam[$findkey+1])) {	
						$bindParam[$value] = $urlParam[$findkey+1];
						$flag = true;
						$temp[$value] = $value;
					}
				}
				$bindParam = array_intersect_key($bindParam, $temp);
			}
			
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
	 * 判断别名URL参数是否存在
	 * @params $currentUrl 当前URL
	 * @return bool
	 * @author wave
	 */
	protected function isAliasUrlParam($currentUrl = ''){
		return  (isset($this->aliasUrl['get'][$currentUrl]) && $this->aliasUrl['get'][$currentUrl]=== true);
	}

	/**
	 * 设置对比数组
	 * @return array
	 * @author wave
	 */
	protected function setDiffArr(){
		if($this->groupName && $this->className && $this->actionName){
			return array($this->groupName,$this->className,$this->actionName);
		}
		return array();
	}

	/**
	 * 判断类的方法是否存在
	 * @return boolen
	 * @author wave
	 */
	protected function isAction($actionName) {
		if( !Ref::hasMethod($actionName)){
			throw new XiaoBoException($actionName.'方法不存在',false);
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
			throw new XiaoBoException($this->className.'控制器不存在',false);
		} 
		return $controllerClass;
	}

	/**
	 * 获取设置默认url
	 * @return string
	 * @author wave
	 */
	protected function getDefualtUrl(){
		$this->default = true;
		$url = DEFAULT_ROUTE;
		if(isset($this->aliasUrl['alias'][$url])){
			$urlArr = array_keys($this->aliasUrl['get']);
			$url = $urlArr[0];
		}
	
		if(empty($_GET)){
			$urlArr = $this->filterArr(explode('/', $url));
			if(count($urlArr) >= 3){
				$_GET[G] = $urlArr[0];
				$_GET[C] = $urlArr[1];
				$_GET[A] = $urlArr[2];
			}
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
		$rootPath = strtolower(ROUTE_DS.basename($this->getPath()));
		if (Server::get('ORIG_PATH_INFO')) {
			$url = Server::get('ORIG_PATH_INFO');
			$urlNum =2;  //伪静态
		} else if (Server::get('PATH_INFO')) {
			$url = Server::get('PATH_INFO');
			$urlNum =2; //伪静态
		} else if (Server::get('REQUEST_URI')) {
			$url =  Server::get('REQUEST_URI');
			$url = $this->substr($url, '','index.php');
			$url = $this->substr($url,'',$rootPath,"stripos",$subflag);
			$url = $this->isAliasUrl($url);
			$urlArr = parse_url($url);

			if(isset($urlArr['path']) && 
			   ($urlArr['path'] !== ROUTE_DS && 
			    strtolower(rtrim($urlArr['path'],ROUTE_DS)) !== $rootPath) 
				&& (rtrim($url,ROUTE_DS) === '/public' && $subflag) )
			{
				$this->get = array();
				$getParam = false;
				$url = $urlArr['path'];
			}
			else if(isset($urlArr['query'])){
				$this->get = $urlArr['query'];
				$getParam = $this->ReturnGetParam($urlArr['query']);
			}
	
			$urlNum = 3; //动态
		} else if (Server::getCliArgs()){  //cli 模式
			$url = Server::getCliArgs();
		}

		if($getParam !== false  && $flag && $urlNum === 3) {
			$url = $getParam;
		}

		if( (strtolower(rtrim($url,ROUTE_DS)) === $rootPath) || 
			(rtrim($url,ROUTE_DS) === '/public' && $subflag) || 
			($url === ROUTE_DS) ||
			(empty($url) && $getParam === false && $urlNum ===3) ) 
		{
			$this->get = array();
			$url = $this->getDefualtUrl();
		}

		//获取别名url
		if(!$this->flag){
		 	$url = urlTo($this->getUrlParamArr,true);
		 }
		return $url;
	}


	/**
	 * 判断是否当前别名url访问,并删除动态参数
	 * @param string $url
	 * @return string
	 * @author wave
	 */
	protected function isAliasUrl($url){
		if(strpos($url,'?') !== false){
			$aliasUrl = substr($url,0,strpos($url, '?'));
			if(isset($this->aliasUrl['alias'][$aliasUrl])){
				return $aliasUrl;
			}
		}
		return $url;
	}


	/**
	 * 设置scheme
	 * @author wave
	 */
	protected function setScheme(){
		$this->scheme = Server::get('REQUEST_SCHEME'); 
	}


	/**
	 * 设置host
	 * @author wave
	 */
	protected function setHost(){
		$this->host =  Server::get('SERVER_NAME');
	}

	/**
	 * 返回GET参入参数
	 * @param string $getStr 获取get参数
	 * @author wave
	 */
	protected function ReturnGetParam($getStr) {	
		parse_str($getStr,$get);
		$getUrl = '';
		$flag = false;
		if(isset($get[A])){
			$getUrl = ROUTE_DS . $get[A] . (empty($getUrl) ? ROUTE_DS : $getUrl);
			$flag = true;
		}

		if(isset($get[C])){
			$getUrl = ROUTE_DS . $get[C] . (empty($getUrl) ? ROUTE_DS : $getUrl);
			$flag = true;
		}

		if(isset($get[G]) ) {
			$getUrl =  ROUTE_DS . $get[G] . (empty($getUrl) ? ROUTE_DS : $getUrl);
			$flag = true;
		}
		
		$this->issetParam((isset($get[G]) ? $get[G] : ''),$flag,G);
		$this->issetParam((isset($get[C]) ? $get[C] : ''),$flag,C);
		$this->issetParam((isset($get[A]) ? $get[A] : ''),$flag,A);
		
		if(empty($getUrl)){
			$getUrl = $this->getDefualtUrl().ROUTE_DS;
		}
		
		return empty($getUrl) ? false : $getUrl;
	}
	
	/**
	 * 判断参数是否存在
	 * @param sting $param 参数
	 * @param bool $flag 标识
	 * @param string $key 键
	 * @author wave
	 */
	protected function issetParam($param,$flag,$key){
		if($flag == true && (!isset($param) || $param == '')){
			throw new XiaoBoException($key."参数不存在",false);
		}
	}

	/**
	 * 获取服务器相对路径目录
	 * @return String 
	 * @author wave
	 */
	protected function getPath() {
		$XmlParse = Container::get('XmlParse');
		$appPath = $XmlParse->getPath(dirname(dirname(__FILE__)));
		return $appPath;
	}

	/**
	 * 引入文件
	 * @param Sting $filePath 文件相对路径
	 * @author wave
	 */
	protected function load($filePath = '') {
		$filePath  = str_replace('//', '/', $filePath);
		return load($filePath);
	}

	/**
	 * 过滤空的数组
	 * @param Array $arr 要过滤的空数组
	 * @return Array 
	 * @author wave
	 */
	protected function filterArr($arr){
		if ( !empty($arr) ) {
			return	array_filter($arr) ? array_values(array_filter($arr)) : array();
		}
		return array();
	}

	/**
	 * 替换第一次出现的字符串
	 * @param string $string 要替换的字符串
	 * @param string $repalce 被替换的字符串
	 * @param string $t_repalce 要替换的字符串
	 * @param string $dist 查询下标函数 strpos/stripos
	 * @param bool $flag 标示是否替换
	 * @return string
	 * @author wave
	 */
	protected function substr($string,$repalce,$t_repalce,$dist="strpos",&$flag = false){
		if(in_array($dist, array("strpos","stripos"))  &&  $dist($string,$t_repalce)  !== false){
			$flag = true;
			return substr_replace($string,$repalce,$dist($string,$t_repalce),strlen($t_repalce));
		}
		$flag = false;
		return $string;
	}


}
