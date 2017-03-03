<?php
class testController  extends Controller{
	public function aaa() {

		//$this->view->assign('a','test');
		//$this->view->display();
	var_dump("1111");
		// $b = LoadModel::load('Mysql',array(),'init');
		// var_dump($b);
	}

	public function sss($a,$b) {
		echo 'ssss'.$a;
		var_dump($b);
	}
}

?>