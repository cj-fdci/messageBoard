
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="mb-3 text-center">
                <?php if ($userDetails['profile_picture']): ?>
                    <img id="profile-preview" src="<?php echo BASE_URL.'app/webroot/uploads/'.$userDetails['profile_picture'] ?>" alt="Profile" class="img-thumbnail mb-2" style="width: 150px; height: 150px;">
                <?php else: ?>
                    <img id="profile-preview" src="https://via.placeholder.com/150" alt="Profile" class="img-thumbnail mb-2" style="width: 150px; height: 150px;">
                <?php endif; ?>
                <br>
                <div id="error-message" class="text-danger mt-2" style="display: none;"></div> <!-- Error message -->
            </div>
            <div class="mb-3">
                <?php echo $this->Form->input('name', [
                    'label' => 'Name', 
                    'class' => 'form-control', 
                    'value' => $userDetails['name'] ?? 'Lisa, Cruz', 
                    'placeholder' => 'Enter your name',
                    'readonly' => true
                ]) ?>
            </div>
            <div class="mb-3">
                <?php echo $this->Form->input('birthdate', [
                    'label' => 'Birthdate', 
                    'type' => 'text', 
                    'class' => 'form-control', 
                    'value' => $userDetails['birthdate'] ?? '1995-07-13',
                    'readonly' => true
                ]) ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label>
                <div class="form-check">
                    <?php
                    echo ucfirst($userDetails['gender']);
                    ?>
                </div>
            </div>
            <div class="mb-3">
                <?php echo $this->Form->input('hubby', [
                    'label' => 'Hubby', 
                    'type' => 'textarea', 
                    'rows' => 3, 
                    'class' => 'form-control', 
                    'value' => $userDetails['hubby'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...'
                ]) ?>
            </div>
        </div>
    </div>
</div>
