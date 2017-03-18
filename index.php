<?php
/**
 * 引入核心文件
 * 注:这是一个强大,自由组装框架 , 只要放进来写一个API调用文件，然后在lib/core/conf/config.xml里面进行配置就可以加载
 * @author wave
 */

header("X-Powered-By:XiaoBoPHP");

if (version_compare("5.3", PHP_VERSION, ">=")) {
     die("install version >= php 5.3");
}

define('LIB_PATH','lib'); //核心目录文件名

//自动加载类,只有调用类的时候才会加载
require  str_replace('\\', '/', dirname(__FILE__)).'/'.LIB_PATH.'/core/AutoLoad.php';



//初始化自动加载类
AutoLoads::init();

//初始化自由组装组件
$XmlParse = new XmlParse();

//加载自定义模块 如:array('thinkphp','yii')
$XmlParse ->loadCustom(array());

//重新选择加载组件 
//$XmlParse->compName = array();




//初始化XML解析器
call_user_func_array(array($XmlParse, "init"),array()); 