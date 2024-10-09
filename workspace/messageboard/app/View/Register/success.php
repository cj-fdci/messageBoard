<div class="thank-you text-center pt-5">
    <h1>Thank You for Registering!</h1>
    <p>Welcome, <?php echo h($user['name']); ?>!</p>
    <p>Your registration was successful.</p>

    <p>Click <a href="<?php echo $this->Html->url(['controller' => 'home', 'action' => 'messages']); ?>">here</a> to proceed to your dashboard directly.</p>
</div>
