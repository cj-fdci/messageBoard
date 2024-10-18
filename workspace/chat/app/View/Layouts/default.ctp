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

    <div class="container">
        <?php echo $this->fetch('content'); ?>
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
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <?php echo $this->Html->script('../app/webroot/js/scripts'); ?>
       <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 
    </body>
</html>
