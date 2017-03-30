<?php
/**
 * 数据库配置文件
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

	//表前缀
	public $tablePrefix = "wp_";

}

