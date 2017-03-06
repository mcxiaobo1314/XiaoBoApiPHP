<?php

/**
 * Sqlite3数据库操作类
 * @author wave
 */


if(!defined('MODEL_TOKEN')) {
	header("HTTP/1.1 404 not found");
	exit('404 not found');
}

class Sqlite extends Sqlite3{

	public $sqldb = SQLITE3;

	public function __construct(){
		$this->open($this->getPath().$this->sqldb);
	}

	/**
	 * 初始化打开数据库
	 * @author wave
	 */
	public function init(){
		
	}


	/**
	 * 获取当前路径
	 * @return string
	 * @author wave
	 */
	private function getPath(){
		return getcwd().ROUTE_DS;
	}
}