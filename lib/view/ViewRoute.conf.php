<?php
/**
 * 视图路由配置文件
 * @author wave
 */
//根目录
if(!defined('APP_ROOT_PATH')) {
	define('APP_ROOT_PATH', 'app'); 

}

//默认访问的目录
if(!defined('DEFAULT_PATH')) {	
	define('DEFAULT_PATH' , 'home'); 
}

//控制器的目录
if(!defined('CONTROLLER')) {	
	define('CONTROLLER','controller');
}

//控制器的后缀名字
if(!defined('CON_SUFFOIX')) {
	define('CON_SUFFOIX','Controller.php'); 
}

if(!defined('ROUTE_DS')) {
	define('ROUTE_DS','/'); 
}

//默认访问的控制器, 写法: 控制器 / 方法名 / 参数
if(!defined('DEFAULT_ROUTE')) {
	define('DEFAULT_ROUTE','/test/aaa'); 
}

//控制器
if(!defined('C')) {
	define('C','c'); 
}

//目录
if(!defined('G')) {
	define('G','g'); 
}

//方法
if(!defined('A')) {
	define('A','a');
}