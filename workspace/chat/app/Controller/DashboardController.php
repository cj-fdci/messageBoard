<?php

use Pusher\Pusher;

class DashboardController extends AppController {
	public $uses = array();

    public function beforeFilter(){
        parent::beforeFilter();

        $this->Auth->allow('index', 'auth', 'add', 'verifypassword', 'sendMessage','getmessage');

        $options = array(
            'cluster' => 'ap1',
            'useTLS' => true
        );
        
        $this->pusher = new Pusher(
            '00b0be9d12434bc5b265',
            'bd82e24ee8a84be62702',
            '1882095',
            $options
        );
    }
    
	public function index (){
        // $this->autoRender = false;
        $data['message'] = 'hello world';

        
	}

    public function sendMessage(){

        $this->autoRender = false;

        $response = [];

        $response['success'] = false;

        if($this->request->is('post')){

            $messageModel = ClassRegistry::init('Message');
            $messageModel->create();

            $sender_name = (isset($this->request->data['sender_name'])?$this->request->data['sender_name']:'');
            $message_text = (isset($this->request->data['message_text'])?$this->request->data['message_text']:'');

            if(!empty($sender_name) && $message_text){
                $data['Message'] = [
                    'sender_name' => $sender_name,
                    'message_text' => $message_text
                ];
    
                if($messageModel->save($data)){
    
                    $response['success'] = true;
                    $this->pusher->trigger('chati-channel', 'chati-event', $data);
                    
                }
            }

        }

        echo json_encode($response);

    }

    public function getMessage(){

        $this->autoRender = false;
        $messageModel = ClassRegistry::init('Message');

        $messageData = $messageModel->find('all',[
            'fields' => [
                'message_id',
                'sender_name', 
                'message_text',
                'created_at'
            ],
            'limit' => 20,
            'order' => ['created_at' => 'DESC']
        ]);

        echo json_encode($messageData);
    }
}