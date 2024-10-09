<div class="container">
    <div class="container mt-5">
        <p class="text-end h5">New Message</p>
        <?php echo $this->Form->create('newMessage', ['url' => ['controller' => 'Home', 'action' => 'sendMessage']]); ?>
        <div class="mb-3">
            <?php
            echo $this->Form->label('user_id', 'Select Recipient', ['class' => 'form-label']); // Label for the select
            echo $this->Form->select('user_id', 
                $userList, 
                [
                    'empty' => 'Search for recipient', 
                    'class' => 'form-control', 
                ]
            );
        ?>
        </div>
          <div class="mb-3">
            <?php echo $this->Form->label('message', 'Message', ['class' => 'form-label']); ?>
            <?php echo $this->Form->textarea('message', [
                'class' => 'form-control',
                'placeholder' => 'Message',
                'rows' => '5',
                'required' => true // Make the textarea required
            ]); ?>
        </div>
            <?php echo $this->Form->button(__('Send Message'), ['class' => 'btn btn-primary']) ?>
        <?php $this->Form->end() ?>
    </div>
</div>  
