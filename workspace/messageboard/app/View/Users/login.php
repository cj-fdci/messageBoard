<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4><?php echo __('Please enter your username and password'); ?></h4>
                </div>
                <div class="card-body">
                    <?php echo $this->Flash->render('auth'); ?>
                    <?php echo $this->Form->create('User', ['class' => 'form-horizontal', 'id' => 'loginForm']); ?>
                        <fieldset>
                            <div class="form-group mb-3">
                                <?php 
                                echo $this->Form->input('email', [
                                    'class' => 'form-control',
                                    'label' => false, // Hide the label to use placeholder instead
                                    'placeholder' => 'Enter your email address'
                                ]); 
                                ?>
                            </div>
                            <div class="form-group mb-3">
                                <?php 
                                echo $this->Form->input('password', [
                                    'class' => 'form-control',
                                    'label' => false, // Hide the label to use placeholder instead
                                    'placeholder' => 'Enter your password',
                                    'type' => 'password'
                                ]); 
                                ?>
                            </div>
                            <div class="text-center">
                                <span>New here? Register 
                                    <?= $this->Html->link('here', ['controller' => 'register', 'action' => 'create']); ?>
                                </span>
                            </div>
                            <div class="text-center mt-2" id="message" style="display:none;"></div>
                        </fieldset>
                        <div class="form-group text-center mt-3">
                            <button class="btn btn-primary btn-block col-12" type="submit">Login</button>                        
                        </div>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
