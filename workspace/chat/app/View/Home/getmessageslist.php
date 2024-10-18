yeah
<div class="message-list">
    <?php foreach ($messages as $message): ?>
        <div class="message">
            <p><?php echo h($message['Messages']['message_content']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<div class="pagination">
    <?php
    echo $this->Paginator->prev('< ' . __('Previous'), null, null, ['class' => 'btn btn-primary']);
    echo $this->Paginator->numbers(); // Generates page numbers
    echo $this->Paginator->next(__('Next') . ' >', null, null, ['class' => 'btn btn-primary']);
    ?>
</div>
