<div class="container">
<h1>Blog posts</h1>
<?php echo $this->Html->link(
    'Add User',
    array('controller' => 'users', 'action' => 'add')
); ?>

<?php if($currentUser['id']): ?>
<?php echo $this->Html->link(
    'Logout',
    array('controller' => 'users', 'action' => 'logout')
); endif;?>

<table class="table table-striped">
    <tr>
        <th>Id</th>
        <th>Title</th>
        
        <th>Created</th>
        <th>Action</th>
    </tr>
    
    <!-- Here is where we loop through our $posts array, printing out post info -->
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
        <td>
            <?php echo $user['User']['email']
            ?>
        </td>
        <td><?php echo (new DateTime($user['User']['created_at']))->format('F j, Y (g:i A)'); ?></td>
        <td>
            <?php
                echo $this->Form->postLink(
                    'Delete',
                    array('action' => 'delete', $user['User']['id']),
                    array('confirm' => 'Are you sure?')
                );
            ?>
            <?php
                echo $this->Html->link(
                    'Edit',
                    array('action' => 'edit', $user['User']['id'])
                );
            ?>
        </td>
    </tr>
    <?php endforeach; ?>

    <?php echo $this->Paginator->numbers(); ?>
    
    <?php unset($users); ?>

    
</table>
</div>