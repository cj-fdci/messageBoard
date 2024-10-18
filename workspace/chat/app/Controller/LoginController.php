<?php
class LoginController extends AppController {
	public $uses = ['User'];

	public function beforeFilter() {
        parent::beforeFilter();
		
		$this->Auth->allow('index', 'auth', 'add', 'verifypassword');
        // always restrict your whitelists to a per-controller basis
        // $this->Auth->allow("ajaxLogin");
    }

	public function index (){

		if ($this->Auth->user()) {
            return $this->redirect(array('controller' => 'home', 'action' => 'messages'));
        }
	}

	public function forgotPassword(){

	}

	public function auth(){

		$this->autoRender = false;

		$response = [];

		$response['success'] = false;
		
		if($this->request->is('post')){

			$username = (isset($this->request->data['username'])?$this->request->data['username']:'');
			
			$userDetails = $this->User->find('first', 
				[
					'conditions' => [
						'username' => $username,
						// 'password' => $this->request->data['password']
						
					],
					'recursive' => -1
				]
			);

			if(isset($userDetails) && !empty($userDetails)){
				$response['success'] = true;
			}else{	
				$response['message'] = 'Please enter your username';
			}

			// print_r($userDetails);
		}else{
			$response['message'] = 'invalid request';
		}

		echo json_encode($response);
	}

	public function verifyPassword(){

		$this->autoRender = false;

		$response = [];

		$response['success'] = false;
		
		if($this->request->is('post')){

			$username = (isset($this->request->data['username'])?$this->request->data['username']:'');
			
			$userDetails = $this->User->find('first', 
				[
					'conditions' => [
						'username' => $username,
						// 'password' => $this->request->data['password']
						
					],
					'recursive' => -1
				]
			);

			if(isset($userDetails) && !empty($userDetails)){

				$passwordHasher = new BlowfishPasswordHasher();
	
				$password = (isset($this->request->data['password'])?$this->request->data['password']:'');
	
				if($passwordHasher->check($password, $userDetails['User']['password'])){
	
					$logModel = ClassRegistry::init('Log');
					$logModel->create();
	
					$data = [
						'user_id' => $userDetails['User']['id']
					];
	
					if($logModel->save($data)){
	
						if($this->Auth->login($userDetails['User']))
						$response['success'] = true;
	
					}
				}else{
					$response['message'] = 'Password you entered is incorrect';
				}
		}else{
			$response['message'] = 'Please enter your username to proceed.';
		}

		echo json_encode($response);
	}
}
}