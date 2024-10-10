<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title_for_layout; ?></title>
    <?php
        echo $this->Html->css('../app/webroot/bootstrap/css/styles');
        echo $this->Html->css('../app/webroot/css/styles');
    ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php if(isset($currentUser['id'])){ ?>

    <div class="container">
       <div class="row justify-content-center">
       <div class="col-6">
        <div class="text-end p-2">
            <div class="row d-flex justify-content-between">
                <div class="col-6 text-start">
                    <?php if(isset($threadId)){ ?>
                    <a href="<?php echo BASE_URL.'home/messages'; ?>">Return</a>
                    <?php }else if(isset($userList)){ ?>

                    <?php } ?>
                </div>
                <div class="col-6">
                    <a href="<?php echo BASE_URL.'Profile'; ?>" class="text-warning text-decoration-none">
                    <img class="profile-image" src="<?php echo BASE_URL.'app/webroot/uploads/'.($currentUser['profile_picture']?$currentUser['profile_picture']:'placeholder.jpg'); ?>" class="rounded-circle" alt="User Image">
                        <span href=""><?php echo $currentUser['name'] ?></span>
                    </a>
                </div>
            <?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); } ?>
            </div>
            </div>
            <?php echo $this->fetch('content'); ?>
            </div>
       </div>
    </div>

    <div id="snackbar"></div>

    <div class="popup-overlay" id="popup-overlay">
        <div class="popup-content">
            <div class="d-flex justify-content-between align-items-center">
                <h5 id="popup-header" class="mb-0 text-center">Are you sure you want to delete this thread?</h5>
                <span class="close-popup text-danger cursor-pointer" id="close-popup">&times;</span>
            </div>
            <p id="popup-message" class="text-center">Please note that deleting this will also delete all the messages from in thread.</p>
            <div class="text-center">
                <button id="confirm-delete" class="btn btn-danger">Delete</button>
                <button id="cancel-delete" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <?php echo $this->Html->script('../app/webroot/bootstrap/js/scripts'); ?>
        <?php echo $this->Html->script('../app/webroot/js/scripts'); ?>
       <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</body>
</html>
