<?php
class PostsController extends AppController {
    // load helpers for use in the view later
    public $helpers = array('Html', 'Form', 'Paginator');
    
	public function index() {
        /** 
         * select * from posts;
         * $posts -> db->fetch_assoc();
         */
        $this->paginate = array(
			'limit' => 2
		);
        $posts = $this->paginate('Post');
        $this->set('posts', $posts);
    }

    public function view ($id = 0) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));

        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('post', $post);
    }

    public function add() {
        // - when submit is detected
        if ($this->request->is('post')) {
            // inform cakephp tht we will save data
            $this->Post->create();

            //insert into posts(title, body) values('title', 'body')
            $savePost = $this->Post->save(array(
                'Post' => array(
                    'title' => $this->request->data['post_title'],
                    'body' => $this->request->data['post_body']
                )
            ));

            // save the actual data
            if ($savePost) {
                $this->Flash->success(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }

            // if cannot save -> show error
            $this->Flash->error(__('Unable to add your post.'));
        }
    }

    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        
        $post = $this->Post->find('first', array(
            'conditions' => array(
                'id' => $id
            )
        ));

        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
    
        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;
            
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to update your post.'));
        }
    
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
    
        if ($this->Post->delete($id)) {
            $this->Flash->success(
                __('The post with id: %s has been deleted.', h($id))
            );
        } else {
            $this->Flash->error(
                __('The post with id: %s could not be deleted.', h($id))
            );
        }
        
        return $this->redirect(array('action' => 'index'));
    }
}