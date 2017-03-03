<?php
/**
 * 配置文件
 * @author wave
 */

if(!defined('APP_ROOT_PATH')) {
	define('APP_ROOT_PATH', 'app'); //代码防止的目录
}
if(!defined('DEFAULT_PATH')) {	
	define('DEFAULT_PATH' , 'home'); //默认访问的目录
}
if(!defined('CONTROLLER')) {	
	define('CONTROLLER','controller'); //控制器的目录
}
if(!defined('CON_SUFFOIX')) {
	define('CON_SUFFOIX','Controller.php'); //控制器的后缀名字
}
if(!defined('ROUTE_DS')) {
	define('ROUTE_DS','/'); 
}
if(!defined('DEFAULT_ROUTE')) {
	define('DEFAULT_ROUTE','/test/aaa'); //默认访问的控制器, 写法: 控制器 / 方法名 / 参数
}
if(!defined('C')) {
	define('C','c'); //控制器
}
if(!defined('G')) {
	define('G','g'); //目录
}
if(!defined('A')) {
	define('A','a'); //方法
}