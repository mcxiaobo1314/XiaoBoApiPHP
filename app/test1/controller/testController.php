<?php
class testController extends Controller {

	public function __construct(){
		parent::__construct();
	}


	public function aaa() {
		$this->view->display();
	}

}
?>