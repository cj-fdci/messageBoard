<?php
class RegisterController extends AppController {
	public $uses = array();

    
	public function index (){

	}

    public function beforeFilter() {
        parent::beforeFilter();

        // always restrict your whitelists to a per-controller basis
        $this->Auth->allow("create");
    }

    public function create() {
    
        $this->loadModel('User');
    
        if ($this->request->is('post')) {

            $this->User->create();

            $userData = $this->request->data;

            if ($this->User->save($userData)) {
                $this->Auth->login($this->User->findById($this->User->id)['User']);
                return $this->redirect(['controller' => 'register', 'action' => 'success']);
            }

            $errors = $this->User->validationErrors;
            $errorMessages = [];

            foreach ($errors as $field => $messages) {
                foreach ($messages as $message) {
                    $errorMessages[] = $message;
                }
            }
            
            if (!empty($errorMessages)) {
                $this->Flash->error(implode(', ', $errorMessages)); 
                $this->Flash->error(__('Unable to register the user. Please, try again.')); 
            }
        }
    }

    public function success(){
        $user = $this->Auth->user(); 
        $this->set('user', $user); 
    }
    
}