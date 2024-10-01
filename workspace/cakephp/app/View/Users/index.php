<!-- File: /app/View/Posts/index.ctp -->



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

<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Edit</th>
        <th>Created</th>
    </tr>
    
    <!-- Here is where we loop through our $posts array, printing out post info -->
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?php echo $user['User']['id']; ?></td>
        <td>
            <?php echo $user['User']['username']
            ?>
        </td>
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
        <td><?php echo $user['User']['created']; ?></td>
    </tr>
    <?php endforeach; ?>

    <?php echo $this->Paginator->numbers(); ?>
    
    <?php unset($users); ?>

    
</table>