<?php
/**
 * xml解析器
 * @author wave
 */

class XmlParse {

	/**
	 * 保存XML对象
	 * @author wave
	 */
	public $xml;

	/**
	 * 加载组件名字
	 * @author wave
	 */
	public $compName = array('conf','helpers','model','view','controller','route','cache');

	/**
	 * 保存XML路径
	 * @author wave
	 */
	protected $pathName = '';

	/**
	 * 全局存放核心文件相对路径
	 * @author wave
	 */
	protected $globalPath = '';

	/**
	 * 组件路径
	 * @author wave
	 */
	protected $compPath = array();

	/**
	 * 版本
	 * @author wave
	 */
	const VERSION = '0.0.1';

	/**
	 * xml配置文件名字
	 * @author wave
	 */
	const XML_CONFIG_FILE = 'config.xml';

	/**
	 * 初始化加载xml配置文件
	 * @author wave
	 */
	public function __construct() {

		$this->pathName = dirname(__FILE__).'/conf/'.self::XML_CONFIG_FILE;
		if(file_exists($this->pathName)) {
			$this->xml = simplexml_load_file($this->pathName);
		}
	}

	/**
	 * 初始化加载自定义模块
	 * @author wave 
	 */
	public function init() {

		foreach($this->compName as $value) {
			$this->loadComp($value);
		}
	}

	/**
     * 加载自定义组件模块
     * @author wave
	 */
	public function loadCustom($custom = array()) {
		if(empty($this->xml->custom)) {
			return false;
		}

		foreach ($custom as $key => $value) {
			$value = (array)$this->xml->custom->$value->path;
			$this->load($this->getPath().$value[0]);
		}
	}

	/**
	 * 获取服务器相对路径目录
	 * @param string $dir 路径
	 * @return String 
	 * @author wave
	 */
	public function getPath($dir = "") {
		if($dir == ""){
			$dir = dirname(__FILE__);
		}
		$currPath = basename($dir); //获取当前文件名
		$searchArr = array('\\',$currPath);
		$replaceArr = array('/','');
		$path = str_replace($searchArr, $replaceArr, $dir);
		return $path;
	}

	/**
	 * 加载自定义组件
	 * @param String $name 加载组件的名称
	 * @author wave
	 */
	protected function loadComp($name) {
		if(empty($this->xml->$name->path) && empty($this->xml->$name->version) ) {
			return false;
		}

		$this->compPath[$name] = (array)$this->xml->$name->path;
		$versionArr = (array)$this->xml->$name->version;
	
		if($versionArr[0] < self::VERSION ) {
			return false;
		}

		$this->load($this->getPath().$this->compPath[$name][0]);
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
}

//调用示例
// $xml = new XmlParse();
// $xml->init();