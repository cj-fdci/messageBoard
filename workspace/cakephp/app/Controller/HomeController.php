<?php
class HomeController extends AppController {
	public $uses = array();
	
	public function beforeFilter (){
		parent::beforeFilter();
		echo "hello from home beforeFilter";

	}

	public function index (){
		echo "die";
		die();
	}

	public function main ($page=''){
		$birthday = "OCTOBER 9, 1994";
		$age = $this->getAge();
		$this->set("user_name", "LESTER AG PADUL");
		$this->set("age", $age);
		$this->set("birthday", $birthday);
	}

	public function tab ($tab_item = "", $param2 = "", $param3 = "") {
		// - get parameters
		var_dump($this->request->query);

		// - get post parameters
		var_dump($this->request->data);
		die();
	}

	public function getAge () {
		return 111;
	}
}