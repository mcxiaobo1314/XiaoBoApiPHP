<?php
class testController  extends Controller{
	public function aaa() {

		$this->view->assign('a','欢迎使用XiaoBoPHPApi组建化框架');
		$this->view->display();
		//$user = $this->LoadModel("wp_users");
		//var_dump($user->find());
		
	}

	public function sss($a,$b) {
		echo 'ssss'.$a;
		var_dump($b);
	}
}

?>