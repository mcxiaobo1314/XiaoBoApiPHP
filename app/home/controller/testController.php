<?php

class testController  extends Controller{

	public function aaa() {
		$this->view->assign('a','欢迎使用XiaoBoPHPApi组建化框架');
		$this->view->display();
		//引入第三api扩展
		// $thinkphp = new thinkphp();
		// $thinkphp->test();		

		//$user = $this->LoadModel('users');
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

}

?>
