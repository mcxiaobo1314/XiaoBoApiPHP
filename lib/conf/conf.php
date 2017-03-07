<?php
/**
 * 配置文件
 * @author wave
 */

//error_reporting(E_ALL);

/**
 * 数据库配置类
 * @author wave
 */

class Config{
	//连接数据库类型  目前支持mysql,sqlite数据库加载 其他需要自己封装
	public $type = "mysql"; 

	//数据库地址
	public $host = "localhost";

	//数据库名
	public $dbname = "wordpress";

	//数据库账号
	public $username = "root";

	//数据库密码
	public $password = "";	

	//数据库编码
	public $charset = "utf8";

	//数据库端口
	public $port = "3306";

}


define("DEBUG", true); //true开启debug false关闭debug


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
if(!defined('MODEL')) {	
	define('MODEL','model'); //模型的目录
}
if(!defined('MOD_SUFFOIX')) {
	define('MOD_SUFFOIX','Model.php'); //模型的后缀名字
}

if(!defined('ROUTE_DS')) {
	define('ROUTE_DS','/'); 
}
if(!defined('DEFAULT_ROUTE')) {
	define('DEFAULT_ROUTE','/test/ccc'); //默认访问,写法:控制器/方法名
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
