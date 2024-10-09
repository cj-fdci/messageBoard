<div class="container">
    <p class="text-start h5">Message List</p>
    <div class="row justify-content-end">
        <div class="col-6">
            <div class="text-end mb-3">
                <a href="<?php echo $this->Html->url(['controller' => 'home', 'action' => 'messages/new']); ?>" class="btn btn-primary">New Message</a>
            </div>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-6">
            <form id="search-message" method="post">
                <input id="search-message-field" type="text" class="form-control" placeholder="Search Messages">
            </form>
        </div>
    </div>
    <h6 class="text-center p-2 text-success">
        <?php if(!empty($this->Flash->render())){?>
        <div id="message-sent"></div>
        <?php } ?>
    </h6>
    <div id="myMessages"></div>
</div>

