<?php

class testController  extends Controller{

	public function __construct(){
		parent::__construct();
		//$this->getUrl();  //获取当前反问url参数
		//$this->getGroup(); //获取当前访问分组
		//$this->getClass(); //获取当前访问类名
		//$this->getAction();  //获取当前访问的方法名
		//$this->getPath(); //获取网站的跟目录
		//$this->getHost(); //获取当前域名
		//$this->getScheme();  //获取是http还是https
	}

	public function aaa() {
		$this->view->assign('a','欢迎使用XiaoBoPHPApi组建化框架');
		$this->view->display();
		//引入第三api扩展
		// $thinkphp = new thinkphp();
		// $thinkphp->test();		

		// $user = $this->LoadModel('users');
		// $user->validate = array(
		// 	array(
		// 		'name'=>'id',
		// 		'reg' => '/^\d+$/',
		// 		'error' => '请输入数字'
		// 	),
		// 	array(
		// 		'name'=>'name',
		// 		'reg' => '/^\d+$/',
		// 		'error' => '请输入数字'
		// 	)

		// );
		// $user->data = array(
		// 	'id' =>'aaaa',
		// 	'name' =>'dddd21111'

		// );
		//if(!$user->validate()){
		// 	var_dump($user->validateErr);
		// }
		//$user->insert();
		//var_dump($user->find());
		//PDO原生操作		
		//var_dump($user->db()->query("select * from wp_users")); 
		 // $a = $this->LoadModel('users');
		 // var_dump($a->where(array("id like"=>'%1'))->find());
		// $a = $this->importModel("WpUsers",array("aa"=>11),"inits");
		// var_dump($a->test());
	}

	public function sss($a,$b) {
		echo 'ssss'.$a;
		var_dump($b);
	}


	public function ccc(){
		//该引入模版，无需编译，支持原生php
		/**
		 * 设置模版变量并引入模版
		 * @param array $array key => 模版变量 value => 变量值
		 * @param string $templeFile 模版文件名
		 */
		$this->view->set(array('a'=>'11111'));
	}

}

?>
