<?php
/**
 * 视图类
 * @author wave
 */

class View {

	//左边界符号
	public $left_delimiter = LEFT_DELIMITER;

	//右边界符号
	public $right_delimiter = RIGHT_DELIMITER;

	/**
	 * 用来保存变量
	 * @author wave
	 */
	public $_value = array();


	/**
	 * 模版引擎的首先字母
	 * @author wave
	 */
	protected $_first = VARIABLE;

	/**
	 * 编译模版的后缀
	 * @author wave
	 */
	protected $suffix = VIEW_SUFFIX;
	
	/**
	 * 保存缓存数组
	 * @author wave
	 */
	public static $CacheData = array();

	/**
	 * 正则
	 * @author wave
	 */
	protected $preg = array(
		//普通变量
		'variable' => '\$[a-zA-Z_][a-zA-Z0-9_]*',
		//普通变量加函数
		'variableFun' => '\$[a-zA-Z_][a-zA-Z0-9_\|]*',
		//数组变量加函数
		'variableArrFun' => '\$[a-zA-Z_][a-zA-Z0-9_]*(\.[a-zA-Z_][a-zA-Z0-9_\|]*)+',
		//文件路径
		'path' => '[a-zA-Z0-9_\.\/]*',
		//文件路径的变量
		'pathVariable'=> '\$[a-zA-Z_][a-zA-Z0-9_\.\/]*'
	);
	/**
	 * 要替换的数组
	 * @author wave
	 */
    protected $tabArr = array('<?php echo ','; ?>');



    /**
     * 获取url参数数组
     * @author wave
     */
    protected $getUrlParamArr = array();

    /**
     * 视图路由标记
     * @author wave
     */
    // protected $flag = false;

	/**
	 * 初始化要加载路由器,并要能获取到控制器的目录，URL访问类名字,类的方法,分组
	 * @param array $param url参数
	 * @param Object $routeObj 路由对象
	 * @author wave
	 */
	public function __construct($param = array()) {
		$this->getUrlParamArr = $param;

		//编译路径
		$this->pathArr['cache'] = $this->getUrlParamArr['controllerPath'].ROUTE_DS.
							'../'.STORAGE.ROUTE_DS.CACHE .ROUTE_DS .$this->getUrlParamArr['group'].
							ROUTE_DS.$this->getUrlParamArr['class'].ROUTE_DS;
		//文件路径
		$this->pathArr['view'] = $this->getUrlParamArr['controllerPath'].ROUTE_DS.$this->getUrlParamArr['group'].
								ROUTE_DS.VIEW.ROUTE_DS. $this->getUrlParamArr['class'] .ROUTE_DS;

	}


	/**
	 * 引入模版
	 * @param String $templateFile 定义模版路径
	 * @author wave
	 */
	public function display($templateFile = null,$htmlFlag=false, $time = 24)  {
		$path = $this->_compresFile($templateFile);
		if(file_exists($path)) {
			require $path;
		}
		
	}

	/**
	 * 赋值变量
	 * @param String $key 定义模版变量
	 * @param String $value 要赋值给模版的变量
	 * @author wave
	 */
	public function assign($key,$val) {
		if(isset($key) && !empty($key)) {
			$this->_value[$key] = $val;
		}
	}

	

	/**
	 * 设置模版变量
	 * @param array $array 模版变量数组
	 * @param string $templateFile 模版名字
	 * @author wave
	 */
	public function set($array= array(),$templateFile = null){
		$tmpfile = !empty($templateFile) ? $templateFile : $this->getUrlParamArr['action'];
		$file_path = $this->pathArr['view'] . $tmpfile . $this->suffix;
		if(!file_exists($file_path)) {
			throw new XiaoBoException("视图文件不存在");
		}
		exit($this->cacheHtml($templateFile,$array,$file_path));
	}



    /**
     * 编译文件
     * @param string $templateFile 文件名
     * @return string
     * @author wave
	 */
	private function _compresFile($templateFile = null) {
		// $this->getUrlParamArr[2] = isset($this->getUrlParamArr[2]) ? $this->getUrlParamArr[2] : NULL;
		// $this->getUrlParamArr[1] = isset($this->getUrlParamArr[1]) ? $this->getUrlParamArr[1] : NULL;
		// $actionName = ($this->isPath() !== false) ? $this->getUrlParamArr[2] : $this->getUrlParamArr[1];
		$tmpfile = !empty($templateFile) ? $templateFile : $this->getUrlParamArr['action'];

		//编译文件路径
		$tmp_path  = $this->pathArr['cache'];
		$file_path = $this->pathArr['view'] . $tmpfile . $this->suffix;
		//定义了文件路径
		if(strpos($templateFile, '.') !== false) {
			//模版文件路径
			$file_path = $this->pathArr['view'] . $tmpfile;
		}
		if(file_exists($templateFile)) {
			$file_path = $templateFile;
		}

		$tmp_name  = 'xb_'.md5($tmpfile).'.php';
		if(!file_exists($file_path)) {
			throw new XiaoBoException("视图文件不存在");
		}
		if(!file_exists($tmp_path)) {
			mkdir($this->pathArr['cache'],0777,true);

		}

		$file_path_time = filemtime($file_path);
		$tmp_path_time = 0;
		//判断缓存文件是否存在
		if(file_exists($tmp_path.'/'.$tmp_name)) {
			$tmp_path_time = filemtime($tmp_path.'/'.$tmp_name);
		}
		
		//判断编译文件是否存在，或者模版文件修改时间小于编译文件修改的时间
		if(!is_file($tmp_path.'/'.$tmp_name) || ($file_path_time > $tmp_path_time)) 
		{
			$html = $this->cacheHtml($tmp_name,array(),$file_path);
			$html = $this->_include($html);
			$html = $this->_if($html);
			$html = $this->_if($html,'elseif');
			$html = $this->_foreach($html);
			$html = $this->_echo($html);
			$html = $this->compress_html($html);
			file_put_contents($tmp_path.'/'.$tmp_name,$html);
		}
		return $tmp_path.'/'.$tmp_name;
	}


	/**
	 * 缓存HTML
	 * @param String $file 缓存文件名
	 * @param String $array 缓存定义的变量
	 * @param String $include_path 缓存文件路径
	 * @author wave
	 */
	private function cacheHtml($file,$array = array(),$include_path) {
		ob_start();
		$file = str_replace(array('/','\\','.'), '_', $file);
		if(!isset(self::$CacheData[$file])) {
			if(!empty($array)) {
				extract($array);
			}
			require $include_path;	
			self::$CacheData[$file] = ob_get_contents();
		}
		ob_clean();
		return self::$CacheData[$file];
	}


	/**
	 * 替换输出
	 * 例子<{$a}>
	 * @param string $html 要替换的HTML
	 * @return resource
	 * @author wave
	 */
	private function _echo($html) {
		$preg = '(('.$this->preg['variableFun'].')|('.$this->preg['variableArrFun'].'))';
		$replaced = '/'.$this->left_delimiter.$preg.$this->right_delimiter.'/is';
		if(!preg_match_all($replaced,$html,$arr)) {
			return $html;
		}
		if(!isset($arr[1]) && count($arr[1]) === 0) {
			return $html;
		}
		$arr[1] = $this->_replace($this->_first, '', $arr[1]);
		$replaceArr = array();
		$strArr = array();
		foreach($arr[1] as $k => $v) {
			$replaceArr[] = $this->analytical($v);
		}
		$html = $this->_replace($arr[0],$replaceArr,$html);
		return $html;
	}

	/**
	 * 字符串解析变量
	 * @param string $str 要解析的字符串
	 * @param bool $flag 是否直接输出变量值,还是解析成字符串变量,默认不开启,真针对普通变量，数组变量无效
	 * @param bool $phpFlag 是否解析成PHP代码字符串,默认开启
	 * @return string
	 * @author wave
	 */
	private function analytical($str,$flag = false,$phpFlag = true){
		$strfun = '';
		if(strpos($str, '|') !== false){
			$strArr = explode('|', $str);
			if(!empty($strArr[1]) && function_exists($strArr[1])) {
				$strfun = ($flag === false) ? $strArr[1].'(' : '';
			}
			$str = !empty($strArr[0]) ? $strArr[0] : $str;
		}
		if(strpos($str, '.') === false) {
			$str =  ($flag === false) ? $strfun.' $this->_value["'.$str.'"] ' : $this->_value["$str"];
		}else {//数组变量解析
			$str = explode('.', $str);
			$str = '["'.implode(''.'"]["', $str).'"]';
			$str = $strfun . '$this->_value'.$str;
		}
		if(!empty($strfun)) {
			$str .= ')';
		}
		return ($phpFlag === true) ? $this->tabArr[0].$str.$this->tabArr[1] : $str;
	}

	/**
	 * 替换引入函数
	 * 例子<{include file="路径加文件"}> 注意：路径带有模版变量则不编译该文件
	 * @param String $html 要替换的HTML
	 * @return HTML
	 * @author wave
	 */
	private function _include($html) {
		$preg = 'include\s+file=\"(('.$this->preg['path'].')|('.$this->preg['pathVariable'].'))\"';
		//正则替换include函数
		$replaced = '/'.$this->left_delimiter.$preg.$this->right_delimiter.'/is';
		if(!preg_match_all($replaced,$html,$arr)){
		    return $html;
		}
		if(!isset($arr[1]) && count($arr[1]) === 0) {
		   	return $html;
		}
        $replaceArr = array();
        foreach($arr[1] as $val) {
            if(strpos($val, $this->_first) !== false) {
                $val = $this->_replace($this->_first, '', $val);
                $valArr = explode('/', $val);
                $val = !empty($valArr[0]) ? $this->analytical($valArr[0],true,false) : $this->analytical($val,true,false); 
                $val .= !empty($valArr[1]) ? '/'.$valArr[1] : '';
            }
        	$path_str = $this->_replace('\\','/',$this->_compresFile($val)); 
			$replaceArr[] = '<?php include "'.$path_str.'"; ?>';
        } 
        $html = $this->_replace($arr[0],$replaceArr,$html);
		return $html;
	}

	/**
	 * 模版标签forach[支持嵌套,与smarty写法差多]
	 * <{foreach item=$arr key=$k val=$v}><{$k}>---<{$v}><{/foreach}>
	 * @param string $html 要替换的HTML的代码
	 * @return HTML
	 * @author wave
	 */
	private function _foreach($html) {
		$preg = 'foreach\s+item\=('.$this->preg['variable'].')\s+key\=('.$this->preg['variable'].')\s+val=('.$this->preg['variable'].')\s*';
		$replaced_start = '/'.$this->left_delimiter.$preg.$this->right_delimiter.'/is';
		if(!preg_match_all($replaced_start,$html,$arr,PREG_SET_ORDER)){
		   	return $html;
		}
		if(empty($arr)){
			return $html;
		}
		$strArr = array();
		$replaceArr = array();
	    foreach($arr as $k => $v) {
	        $str = '<?php foreach(';
	        $firstStr = '';
	        foreach ($v as $key => $value) {
        	   ($key !== 0) &&  $value = $this->_replace($this->_first,'',$value);
               switch ($key) {
	                case 0:
		                $strArr[] = $value;
		                break;
	                case 1:
		                $firstStr = $this->analytical($value,false,false);
		                $str .= $firstStr.' as ';
		                break;
	                case 2:
		                $str .= $this->analytical($value,false,false).' => ';
		                break;
	                case 3:
		                $str .= $this->analytical($value,false,false).' ){  ?>'; 
						$replaceArr[] = $str;
						break;
	            }
	        }
	    }
	    $html = $this->_replace($strArr, $replaceArr, $html);
	    $html = $this->_replace($this->left_delimiter.'/foreach'.$this->right_delimiter, '<?php } ?>', $html);
		return $html;
	}

	/**
	 * 替换IF语句[支持嵌套]
	 * 例子<{if $a == $b }>123<{else}>2323<{/if}> 
	 * @param string $html 值
	 * @return HTML
	 * @return wave
	 */
	private function _if($html,$if = 'if') {
		if(preg_match_all('/'.$this->left_delimiter.$if.'\s+(.*?)'.$this->right_delimiter.'/is', $html, $arr)) {
			if(empty($arr[0]) || empty($arr[1]) ){
				return $html;
			}
			$strArr = array();
			foreach( $arr[1] as $val ) {
				if( preg_match_all('/'.$this->preg['variableArrFun'].'/is', $val, $varr) ) {
					foreach($varr[0] as $vals) {
						$str = $this->_replace($this->_first, '', $vals);
						$tStr = $this->_replace($this->tabArr, '', $this->analytical($str));
						$val = $this->substr($val,$tStr,$vals);
					}
				}

				if(preg_match_all('/'.$this->preg['variableFun'].'/is', $val, $varr)) {
					foreach($varr[0] as $vals) {
						if($vals !== '$this'){
							$str = $this->_replace($this->_first, '', $vals);
							$tStr = $this->_replace($this->tabArr, '', $this->analytical($str));
							//$val = substr_replace($val, $tStr, strpos($val, $vals),strlen($vals));
							$val = $this->substr($val,$tStr,$vals);
						}
					}
				}
				if($if == 'if'){
					$strArr[] = '<?php if('.$val.'){ ?>';
				}elseif($if == 'elseif'){
					$strArr[] = '<?php }else if('.$val.'){ ?>';
				}
				
			}
			$replaceArr = array(
				're' => array(
					$this->left_delimiter.'/if'.$this->right_delimiter,
					$this->left_delimiter.'else'.$this->right_delimiter
				),
				'data' => array(
					'<?php } ?>',
					'<?php }else{  ?>'
				)
			);
			if(count($strArr) === count($arr[0])) {
				$html = $this->_replace($arr[0],$strArr,$html);
				$html = $this->_replace($replaceArr['re'],$replaceArr['data'],$html);
			}
			
		}
		return $html;
	}


   /** 
	* 压缩html : 清除换行符,清除制表符,去掉注释标记 
	* @param $string 
	* @return 压缩后的$string 
	* */ 
	protected function compress_html($string) {
		$string = str_replace("\r\n", '', $string); //清除换行符
		$string = str_replace("\n", '', $string); //清除换行符
		$string = str_replace("\t", '', $string); //清除制表符
		$pattern = array (
			"/> *([^ ]*) *</", //去掉注释标记
			"/[\s]+/",
			"/<!--[^!]*-->/",
			"/\" /",
			"/ \"/",
			"'/\*[^*]*\*/'"
		);
		$replace = array (
			">\\1<",
			" ",
			"",
			"\"",
			"\"",
			""
		);
		return preg_replace($pattern, $replace, $string);
	}

	/**
	 * 替换截取字符
	 * @param Array or String $replaced 要替换的
	 * @param Array or String $replace 被替换的
	 * @param String $str 查找字符串
	 * @param String $explode 截取的字符串
	 * @return Array|string
	 * @author wave
	 */
	private function _replace($replaced,$replace,$str,$explode = NULL) {
		if(!empty($replaced)) {
			$str = str_replace($replaced, $replace, $str);
		}
		if($explode !== NULL) {
			$arr = array_filter(explode($explode, $str));
			$arr = array_values($arr);
			return $arr;
		}
		return $str;
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
