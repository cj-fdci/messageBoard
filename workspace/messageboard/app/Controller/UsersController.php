<?php

App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UsersController extends AppController {
    public $uses = ['User'];
	public function beforeFilter() {
        parent::beforeFilter();

        // always restrict your whitelists to a per-controller basis
        $this->Auth->allow("ajaxLogin");
    }

    public function login() {

        if ($this->Auth->user()) {
            return $this->redirect(array('controller' => 'home', 'action' => 'messages'));
        }

        if ($this->request->is('post')) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'email' => $this->request->data['User']['email'],
                    'password' => $this->request->data['User']['password']
                )
            ));
            
            $didLogin = $this->Auth->login($user['User']);
            // $didLogin = $this->Auth->login();
            
            if ($didLogin) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function ajaxLogin () {

        $response = array();

        $user = $this->User->find('first', array(
            'conditions' => array(
                'email' => $this->request->data['email'],
                // 'password' => $this->request->data['password']
            )
        ));

        $response['status'] = false;

        if($user){
            $passwordHasher = new BlowfishPasswordHasher();

            if ($passwordHasher->check($this->request->data['password'], $user['User']['password'])) {
                // If the password matches, log in the user
                $response['status'] = $this->Auth->login(isset($user['User'])?$user['User']:null);
                $user['User']['last_login_time'] = date('Y-m-d H:i:s');

                $this->User->id = $user['User']['id'];
                $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
            }
        }
   
        $response['user'] = $this->Auth->user();
        $response['message'] = $response['status']?"Login Success":"Incorrect username or password.";

        echo json_encode($response);

        die();
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }
    
    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->findById($id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
    }

    public function updateProfile(){
        
    }

    public function delete($id = null) {
        // Prior to 2.5 use
        // $this->request->onlyAllow('post');

        $this->request->allowMethod('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }

}