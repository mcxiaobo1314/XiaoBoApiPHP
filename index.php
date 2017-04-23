<?php
/**
 * 引入核心文件
 * 注:这是一个强大,自由组装框架 , 只要放进来写一个API调用文件，然后在lib/core/conf/config.xml里面进行配置就可以加载
 * @author wave
 */

header("X-Powered-By:XiaoBoPHP");

ini_set('display_errors','Off');

if (version_compare("5.3", PHP_VERSION, ">=")) {
     die("install php version >=  5.3");
}

define('APP_ROOT_PATH', 'app'); //网站app代码目录
define('LIB_PATH','lib'); //核心目录文件名
define('STORAGE', 'storage');  //web存储文件
define('LOG', 'log'); //日志目录

date_default_timezone_set('PRC');

//自动加载类,只有调用类的时候才会加载
require  str_replace('\\', '/', dirname(__FILE__)).'/'.LIB_PATH.'/core/AutoLoad.php';

//初始化自动加载类
AutoLoads::init();
//初始化自定义错误
XiaoBoError::init();

//初始化自由组装组件
Container::instace('XmlParse');

$XmlParse = Container::get('XmlParse');

//初始化XML解析器
$XmlParse->init("Bootstrap::app");
