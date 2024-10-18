<?php
class RegisterController extends AppController {
	public $uses = array();

    
	public function index (){

	}

    public function beforeFilter() {
        parent::beforeFilter();

        // always restrict your whitelists to a per-controller basis
        $this->Auth->allow("create", 'index');
    }

    public function create() {

        $this->autoRender = false;

        $response = [];

        $response['success'] = false;

        $userModel = ClassRegistry::init('User');
    
        if ($this->request->is('post')) {

            $userModel->create();

            $userData = $this->request->data;

            if ($userModel->save($userData)) {
                $this->Auth->login($userModel->findById($userModel->id)['User']);
                $response['success'] = true;
            }

            $errors = $userModel->validationErrors;

            $errorMessages = [];

            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }

            if(isset($errors) && !empty($errors)){
                $response['message'] = implode(', ', $errorMessages);
            }

        }

        echo json_encode($response);
    }

    public function success(){
        $user = $this->Auth->user(); 
        $this->set('user', $user); 
    }
    
}