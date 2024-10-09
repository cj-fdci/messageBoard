<?php
class ProfileController extends AppController {
	public $uses = array();

    public function beforeFilter (){
		parent::beforeFilter();
	
        $this->loadModel('User');
	}
    
	public function index (){
        
	}

    public function test(){
        $this->autoRender = false;
    }

    // this is the update profile code

    public function update() {
        $this->autoRender = false;

        $response = [];

        $response['success'] = false;

        if ($this->request->is('post')) {
            
            $userData = $this->request->data['Users'];
    
            if (!empty($this->request->data['Users']['profile_pic']['name'])) {
         
                $file = $this->request->data['Users']['profile_pic'];
                $fileName = time().$file['name']; 
                $fileTmpPath = $file['tmp_name']; 
                $fileError = $file['error']; 
                $fileSize = $file['size'];
            
                $uploadDir = WWW_ROOT . 'uploads' . DS;
                $uploadFile = $uploadDir . basename($fileName);
    
                if ($fileError === UPLOAD_ERR_OK) {
                 
                    if (move_uploaded_file($fileTmpPath, $uploadFile)) {
                     
                        $userData['profile_picture'] = $fileName;
                    } else {
                        $response['message'] = 'Error moving the uploaded file.';
                    }
                } else {
                    $response['message'] = 'Error in file upload: ' . $fileError;
                }
            }

            unset($userData['profile_pic']);

            if(!$userData['password']){
                unset($userData['password']);
            }

            $currentUserId = $this->Auth->user('id'); 
    
            $userDetails = $this->User->find('first',
                [
                    'conditions' => [
                        'User.id' => $currentUserId
    
                ]]
            );

            $this->User->id = $currentUserId;

            $userDetails = $this->User->set($userData);

            if(!isset($userDetails['password'])){
                unset($userDetails['password']);
            }
           
            if ($this->User->save($userDetails)) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
            }
        }

        echo json_encode($response);

    }
    
    public function view($userId = 0){

        $userDetails = $this->User->find('first',
        [
            'conditions' => [
                'User.id' => $userId

        ]]
        );

        $this->set('userDetails', $userDetails['User']);
    }
    
    
}