<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5">
                <div class="card-body">
                    <h1 class="text-center">Registration</h1>
                    <h6 class="text-center p-2" id="message_error"><?php echo $this->Flash->render(); ?></h6>
                    <?php echo $this->Form->create('User', ['id' => 'registrationForm']) ?>
                    
                    <div class="form-group">
                        <?php echo $this->Form->label('name', 'Name') ?>
                        <?php echo $this->Form->text('name', ['class' => 'form-control', 'required' => false]) ?>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->label('email', 'Email') ?>
                        <?php echo $this->Form->email('email', ['class' => 'form-control', 'required' => false]) ?>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->label('password', 'Password') ?>
                        <?php echo $this->Form->password('password', ['class' => 'form-control', 'required' => false]) ?>
                    </div>

                    <div class="form-group">
                        <?php echo $this->Form->label('confirm_password', 'Confirm Password') ?>
                        <?php echo $this->Form->password('confirm_password', ['class' => 'form-control', 'required' => false]) ?>
                    </div>

                    <div class="text-center mt-4">
                    <?php echo $this->Form->end(__('Submit')); ?>
                    </div>

                    <?php echo $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>