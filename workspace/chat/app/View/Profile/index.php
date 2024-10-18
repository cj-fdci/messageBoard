<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div id="update-response" style="display:none;"></div>
            <?php echo $this->Form->create('Users', ['type' => 'file', 'id' => 'updateProfileForm']) ?>
            <div class="mb-3 text-center">
                <?php if ($currentUser['profile_picture']): ?>
                    <img id="profile-preview" src="<?php echo BASE_URL.'app/webroot/uploads/'.$currentUser['profile_picture'] ?>" alt="Profile" class="img-thumbnail mb-2" style="width: 150px; height: 150px;">
                <?php else: ?>
                    <img id="profile-preview" src="https://via.placeholder.com/150" alt="Profile" class="img-thumbnail mb-2" style="width: 150px; height: 150px;">
                <?php endif; ?>
                <br>
                <label for="profile_pic" class="btn btn-secondary btn-sm mt-2">Upload Picture</label>
                <?php echo $this->Form->file('profile_pic', ['class' => 'form-control-file', 'style' => 'display: none;', 'id' => 'profile_pic']); ?>
                <div id="error-message" class="text-danger mt-2" style="display: none;"></div> <!-- Error message -->
            </div>

            <div class="mb-3">
                <?php echo $this->Form->input('name', [
                    'label' => 'Name', 
                    'class' => 'form-control', 
                    'value' => $currentUser['name'] ?? 'Lisa, Cruz', 
                    'placeholder' => 'Enter your name'
                ]) ?>
            </div>

            <div class="mb-3">
                <?php echo $this->Form->input('email', [
                    'label' => 'Email', 
                    'type' => 'email', 
                    'class' => 'form-control', 
                    'value' => $currentUser['email'] ?? '', 
                    'placeholder' => 'Enter your email'
                ]) ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Birthdate</label>
                <?php echo $this->Form->control('birthdate', [
                    'label' => 'Birthdate', 
                    'type' => 'date', 
                    'class' => 'form-control', 
                    'value' => $currentUser['birthdate'] ?? '1995-07-13'
                ]) ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <div class="form-check">
                    <?php
                    echo $this->Form->radio(
                        'gender', 
                        [
                            'male' => 'Male', 
                            'female' => 'Female'
                        ],
                        [
                            'legend' => false,
                            'separator' => '<br/>',
                            'default' => (isset($currentUser['gender']) ? $currentUser['gender'] : null) // Preselect gender
                        ]
                    );
                    ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Hobby</label>
                <?php echo $this->Form->textarea('hubby', [
                    'label' => 'Hubby', 
                    'type' => 'textarea', 
                    'rows' => 3, 
                    'class' => 'form-control', 
                    'value' => $currentUser['hubby'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...'
                ]) ?>
            </div>

            <div class="mb-3">
                <?php echo $this->Form->control('password', [
                    'label' => 'Password', 
                    'type' => 'password', 
                    'class' => 'form-control', 
                    'placeholder' => 'Enter new password (leave blank to keep current)'
                ]) ?>
            </div>

            <div class="d-grid">
                <?php echo $this->Form->button(__('Update'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php echo $this->Form->end() ?>
        </div>
    </div>
</div>
