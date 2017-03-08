<?php

class testController  extends Controller{
	public function aaa() {
		$this->view->assign('a','欢迎使用XiaoBoPHPApi组建化框架');
		$this->view->display();
		//引入第三api扩展
		// $thinkphp = new thinkphp();
		// $thinkphp->test();		


		// $user = $this->LoadModel('user');
		// $user->data = array(
		// 	'id' =>10,
		// 	'name' =>'dddd2'

		// );
		// $user->insert();
		// var_dump($user->find());
		//var_dump($user->query("insert into user(id,name)values(4,'aaaa')"));
		// $a = $this->LoadModel('wp_users');
		// var_dump($a->find());
		// $a = $this->importModel("WpUsers",array("aa"=>11),"inits");
		// var_dump($a->test());
	}

	public function sss($a,$b) {
		echo 'ssss'.$a;
		var_dump($b);
	}

}

?>