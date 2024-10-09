<div class="container">
    <p class="text-start mb-4 h5">Message Detail</p>

    <?php echo $this->Form->create('User', ['class' => 'mb-4']); ?>
        <div class="row justify-content-end">
            <div class="col-6 mb-2">
                <?php echo $this->Form->textarea('message', [
                    'class' => 'form-control',
                    'rows' => '3',
                    'placeholder' => 'Enter your message here...'
                ]); ?>
            </div>
        </div>
        <?php echo $this->Form->hidden('recipient_id', ['value' => $recipientId]); ?>
        <?php echo $this->Form->hidden('thread_id', ['value' => $threadId]); ?>
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Reply Message</button>
        </div>
    <?php echo $this->Form->end(); ?>
        <div id="thread-messages" data-thread="<?php echo $threadId; ?>" class="mb-4">
    </div>
</div>
