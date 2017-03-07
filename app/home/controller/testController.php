<?php

class testController  extends Controller{
	public function aaa() {
		$this->view->assign('a','欢迎使用XiaoBoPHPApi组建化框架');
		$this->view->display();
		//执行sqlite3数据库语句
		//var_dump($this->LoadModel('')->query('select * from user where id=1'));
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