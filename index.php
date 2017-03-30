<?php
/**
 * 引入核心文件
 * 注:这是一个强大,自由组装框架 , 只要放进来写一个API调用文件，然后在lib/core/conf/config.xml里面进行配置就可以加载
 * @author wave
 */

header("X-Powered-By:XiaoBoPHP");

ini_set('display_errors','Off');

if (version_compare("5.3", PHP_VERSION, ">=")) {
     die("install version >= php 5.3");
}

define('APP_ROOT_PATH', 'app'); //代码防止的目录
define('LIB_PATH','lib'); //核心目录文件名

//自动加载类,只有调用类的时候才会加载
require  str_replace('\\', '/', dirname(__FILE__)).'/'.LIB_PATH.'/core/AutoLoad.php';

//初始化自动加载类
AutoLoads::init();
//初始化自定义错误
XiaoBoError::init();

Bootstrap::instace();
