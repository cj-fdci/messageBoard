<?php
use Cake\I18n\Time;

class HomeController extends AppController {
	public $uses = array();

	public $components = array('Paginator');

    public $paginate = array(
        'limit' => 25,
        'contain' => array('message_content')
    );
	
	public function beforeFilter (){
		parent::beforeFilter();
		// echo "hello from home beforeFilter";
		$this->loadModel('Users');
		$this->loadModel('Threads');
		$this->loadModel('Messages');
	}

	// public function index (){
	// 	echo "die";
	// 	die();
	// }

	public function main ($page=''){
		$birthday = "OCTOBER 9, 1994";
		$age = $this->getAge();
		$this->set("user_name", "LESTER AG PADUL");
		$this->set("age", $age);
		$this->set("birthday", $birthday);
	}

	public function getThreads($user, $limit = 10, $searchTerm = ''){

		if(!empty($searchTerm)){
			$searchTerm = array(
				'OR' => array(
					'Messages.message_content LIKE' => '%' . $searchTerm . '%',
					'Users.name LIKE' => '%' . $searchTerm . '%'
				)
			);
			
		}

		return $this->Messages->find('all', [
			'fields' => [
				'Messages.thread_id',
				'latest_message_id' => 'MAX(Messages.ms_id) AS latest_message_id',
				'last_message_time' => 'MAX(Messages.created_at) AS last_message_time',
				"(SELECT message_content 
				 FROM messages 
				 WHERE messages.thread_id = Messages.thread_id 
				 ORDER BY created_at DESC 
				 LIMIT 1) AS latest_message_content",
				"(CASE 
					WHEN Messages.sender_id = {$user['id']} THEN 
						(SELECT name FROM users WHERE users.id = Messages.recipient_id)
					ELSE 
						(SELECT name FROM users WHERE users.id = Messages.sender_id)
				END) AS name",
				"(CASE 
					WHEN Messages.sender_id = {$user['id']} THEN 
						(SELECT id FROM users WHERE users.id = Messages.recipient_id)
					ELSE 
						(SELECT id FROM users WHERE users.id = Messages.sender_id)
				END) AS id",
				"(CASE 
					WHEN Messages.sender_id = {$user['id']} THEN 
						(SELECT profile_picture FROM users WHERE users.id = Messages.recipient_id)
					ELSE 
						(SELECT profile_picture FROM users WHERE users.id = Messages.sender_id)
				END) AS profile_picture",
			],
			'joins' => [
				[
					'table' => 'threads',
					'alias' => 'Threads',
					'type' => 'INNER',
					'conditions' => [
						'Threads.thread_id = Messages.thread_id'
					]
				],
				[
					'table' => 'users',
					'alias' => 'Users',
					'type' => 'INNER',
					'conditions' => [
						'Users.id IN (Messages.recipient_id, Messages.sender_id)'
					]
				]
			],
			'group' => ['Messages.thread_id', 'recipient_id', 'sender_id'], // Group by thread_id only
			'order' => ['last_message_time' => 'DESC'],
			'conditions' => [
				'OR' => [
					'Messages.sender_id' => $user['id'],
					'Messages.recipient_id' => $user['id']
				],
				'Messages.sender_id != Messages.recipient_id',
				$searchTerm
			],
			'limit' => $limit,
			'offset' => ($limit == 0) ? 0 : ($limit - 10)
		]);
			
		
	
	}

	public function messages($param = '', $messageLimit = 0){

		switch($param){
			case 'new':
				$this->newMessage();
				break;
			case 'view':
				$this->viewMessage($messageLimit);
				break;
			default;
		}

	}

	public function newMessage(){

		$user = $this->Auth->user(); 

		$users = $this->Users->find('all', [
			'conditions' => [
				'NOT' => ['Users.id' => $user['id']]
			]
		]);
		$userList = [];

		foreach ($users as $user) {
			$userList[$user['Users']['id']] = $user['Users']['name'];
		}
		$this->set(compact('userList'));
		$this->render('newmessage');

	}

	public function replyMessage(){

		$user = $this->Auth->user(); 

		$response = array();
		$response['success'] = false;

		if(empty($this->request->data['User']['message'])){
			$response['message'] = 'Please enter your message';
		} else if ($this->request->is('post')){

			$messageDetails = [
				'Messages' => [
					'thread_id' => $this->request->data['User']['thread_id'],
					'sender_id' => $user['id'],
					'recipient_id' => $this->request->data['User']['recipient_id'],
					'message_content' => strip_tags($this->request->data['User']['message']),
					'created_at' => date('Y-m-d H:i:s'),
					'created_ip' => $this->getUserIP()
				]
			];

			if ($savedMessageDetail = $this->Messages->save($messageDetails)) {
				$response['success'] = true;

				$profileImageURL = BASE_URL.'app/webroot/uploads/'.($user['profile_picture']?$user['profile_picture']:'placeholder.jpg');
				$time = (new DateTime($messageDetails['Messages']['created_at']))->format('F j, Y : g:i A');
				$userName = $user['name'];
				$messageContent = $messageDetails['Messages']['message_content'];
				$messageId = $savedMessageDetail['Messages']['id'];

				$response['html'] = <<<HTML
				<div class="card mb-3 message-box">
					<div class="card-body">
						<div class="d-flex align-items-start justify-content-between">
							<div class="position-absolute p-2 top-0 start-0">
								<span class="bg-danger p-1 open-popup delete-message rounded" data-id="{$messageId}">
									<i class="fas fa-trash text-white"></i>
								</span>
							</div>
							<div class="flex-grow-1 ms-3">
								<div class="d-flex justify-content-between">
									<span class="text-muted small">
									{$time}
									</span>
									<span class="text-muted d-block">
									{$userName}<span class="text-primary">(You)</span>
									</span>
								</div>
							<p class="card-text message-content">{$messageContent}</p>
							<a href="#" class="toggle-message" style="display:none;">Show More</a>
							</div>
						<div class="flex-shrink-0">
							<img src="{$profileImageURL}" class="profile-image" alt="User Image">
						</div>
						</div>
					</div>
				</div>
HTML;

			}
		}

		echo json_encode($response);

		$this->autoRender = false;
	}

	public function viewMessage($thread_id = ''){
		
		$recipientId = $this->Messages->find('first', [
			'fields' => ['Messages.recipient_id', 'Messages.sender_id'], 
			'conditions' => [
				'Messages.thread_id' => $thread_id
			]
		]);

		$recipientId = ($recipientId['Messages']['recipient_id'] == $recipientId['Messages']['recipient_id'])?$recipientId['Messages']['recipient_id']:$recipientId['Messages']['sender_id'];

		$this->set('recipientId', $recipientId);
		$this->set('threadId', $thread_id);
		$this->render('replymessage');
	}

	public function sendMessage() {
		if ($this->request->is('post')) {

			$user = $this->Auth->user(); 

			$threadDetails = [
				'Threads' => [
					'created_by' => $user['id'],
					'recipient_id' => $this->request->data['newMessage']['user_id'],
					'created_at' => date('Y-m-d H:i:s')
				]
			];

			if($savedThread = $this->Threads->save($threadDetails)){

				$messageDetails = [
					'Messages' => [
						'thread_id' => $savedThread['Threads']['id'],
						'sender_id' => $user['id'],
						'recipient_id' => $this->request->data['newMessage']['user_id'],
						'message_content' => strip_tags($this->request->data['newMessage']['message']),
						'created_at' => date('Y-m-d H:i:s'),
						'created_ip' => $this->getUserIP()
					]
				];
	
				if ($this->Messages->save($messageDetails)) {
					$this->Flash->success('Your message has been sent successfully.');
				} else {
					$this->Flash->error('Failed to send the message.');
				}
			}
		}
	
		$this->redirect(['controller' => 'home', 'action' => 'messages']);
		$this->autoRender = false;
	}

	public function getPhpVersion(){

		//my curiousity leads me here.
		echo phpversion();
		$this->autoRender = false;
	}

	public function getUserIP() {
	
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	public function renderThreads($messageLimit = 10, $searchKey = ''){

		$response = array();
		$response['success'] = false;

		$messageBoxes = null;

		$user = $this->Auth->user(); 
        $this->set('user', $user); 

		$userMessages = $this->getThreads($user, $messageLimit, $searchKey);
		$response['count'] = count($userMessages);

		foreach($userMessages as $message){ 

		$messageOwner = $message[0]['name'];
		$lastMessage = $message[0]['latest_message_content'];
		$messageDate = (new DateTime($message[0]['last_message_time']))->format('F j, Y : g:i A');
		$profileImage = BASE_URL.'app/webroot/uploads/'.($message[0]['profile_picture']?$message[0]['profile_picture']:'placeholder.jpg');
		$profileId = $message[0]['id'];
		$threadId = $message['Messages']['thread_id'];

		$messageBoxes .= <<<HTML
			<div class="card mb-3 message-box">
				<div class="card-body">
					<div class="d-flex align-items-start">
						<div class="p-2">
							<a href="/messageboard/profile/view/{$profileId}" class="text-decoration-none w-100">
								<img class="profile-image rounded-circle" src="{$profileImage}" alt="User Image">
								<div class="text-center mt-2">
									<span>{$messageOwner}</span>
								</div>
							</a>
						</div>
			
						<div class="flex-grow-1 ms-3 position-relative">
							<a href="/messageboard/home/messages/view/{$threadId}" class="text-decoration-none w-100">
								<div class="d-flex flex-column h-100">
									<p class="card-text mb-1">$lastMessage</p>
									<div class="mt-auto pt-4">
										<p class="card-text text-muted mb-0 small text-end"> 
											{$messageDate}
										</p>
									</div>
								</div>
							</a>
							<div class="position-absolute top-0 end-0" style="margin-top: 8px; margin-right: 8px;">
								<span class="bg-danger p-1 open-popup delete-thread rounded" data-thread="{$threadId}">
									<i class="fas fa-trash text-white"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
	HTML;
		}
		$this->autoRender = false;

		if($messageBoxes){
			$response['success'] = true;
		}
		
		$response['html'] = $messageBoxes;
		
		echo json_encode($response);
	
	}

	public function deleteThread(){

		$response = [];

		$response['success'] = false;
		if($this->request->is('post')){

			$threadId =$this->request->data['thread_id'];
			
			$threadIsDeleted = $this->Threads->deleteAll(['Threads.thread_id' => $threadId], false);

			if($threadIsDeleted){
				$messagesIdDeleted = $this->Messages->deleteAll(['Messages.thread_id' => $threadId], false);
				$response['success'] = true;
			}
		}

		echo json_encode($response);

		$this->autoRender = false;

	}

	public function deleteMessage(){

		$response = [];

		$response['success'] = false;
		if($this->request->is('post')){

			$threadId =$this->request->data['ms_id'];
			
			$messageIsDeleted = $this->Messages->deleteAll(['Messages.ms_id' => $threadId], false);

			if($messageIsDeleted){
				$response['success'] = true;
			}
		}

		echo json_encode($response);

		$this->autoRender = false;

	}

	public function getThreadMessages($threadId = 0, $limit = 10){

		$this->autoRender = false;

		$response = [];

		$response['success'] = false;

		$threadMessages = $this->Threads->find('all', [
			'fields' => [
				'Threads.*', 
				'Messages.*', 
				'Users.*' 
			],
			'joins' => [
				[
					'table' => 'messages',
					'alias' => 'Messages',
					'type' => 'INNER',
					'conditions' => [
						'Messages.thread_id = Threads.thread_id'
					]
				],
				[
					'table' => 'users',
					'alias' => 'Users',
					'type' => 'INNER',
					'conditions' => [
						'Users.id = Messages.sender_id'
					]
				]
			],
			'order' => ['Messages.created_at' => 'DESC'],
			'conditions' => [
				'Threads.thread_id' => $threadId
			],
			'limit' => $limit,
			'offset' => ($limit == 10)?0:($limit-10)
		]);

		$user = $this->Auth->user(); 

		$response['messages_count'] = count($threadMessages);

		$response['html'] = ($this->renderThreadMessages($threadMessages, $user));

		if($response['html']){
			$response['success'] = true;
		}

		echo json_encode($response);
	}

	public function renderThreadMessages($threadMessages, $currentUser) {
		$renderedMessages = '';
		
		foreach ($threadMessages as $message) {

			// Extract message details
			$isOwner = $currentUser['id'] == $message['Users']['id'];
			$messageCreatedAt = (new DateTime($message['Messages']['created_at']))->format('F j, Y : g:i A');
			$messageOwner = h($message['Users']['name']);
			$messageContent = h($message['Messages']['message_content']);
			$senderProfile = BASE_URL . 'app/webroot/uploads/' . (!empty($message['Users']['profile_picture']) ? $message['Users']['profile_picture'] : 'placeholder.jpg');
			$messageId = $message['Messages']['ms_id'];

			if ($isOwner) {
				$renderedMessages .= <<<HTML
				<div class="card mb-3 message-box">
					<div class="card-body">
						<div class="d-flex align-items-start justify-content-between">
						<div class="position-absolute p-2 top-0 start-0">
							<span class="bg-danger p-1 open-popup delete-message rounded" data-id="{$messageId}">
								<i class="fas fa-trash text-white"></i>
							</span>
						</div>
							<div class="flex-grow-1 ms-3">
								<div class="d-flex justify-content-between">
									<span class="text-muted small">{$messageCreatedAt}</span>
									<span class="text-muted d-block">
										{$messageOwner}<span class="text-primary"> (You)</span>
									</span>
								</div>
								<p class="card-text message-content">{$messageContent}</p>
								<a href="#" class="toggle-message" style="display: none;">Show More</a>
							</div>
							<div class="flex-shrink-0">
								<img class="profile-image rounded-circle" src="{$senderProfile}" alt="User Image">
							</div>
						</div>
					</div>
				</div>
	HTML;
			} else {
				// sa lain
				$renderedMessages .= <<<HTML
				<div class="card mb-3">
					<div class="card-body">
						<div class="d-flex align-items-start justify-content-between">
							<div class="flex-shrink-0 me-2">
								<img class="profile-image rounded-circle" src="{$senderProfile}" alt="User Image">
							</div>
							<div class="flex-grow-1">
								<div class="d-flex justify-content-between align-items-center">
									<span class="text-muted d-block">{$messageOwner}</span>
									<span class="text-muted small">{$messageCreatedAt}</span>
								</div>
								<p class="card-text">{$messageContent}</p>
							</div>
						</div>
					</div>
				</div>
	HTML;
			}
		}
	
		return $renderedMessages;
	}
	
}