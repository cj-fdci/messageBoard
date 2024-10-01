//app/View/Users/login.ctp

<div class="users form">
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>
            <?php echo __('Please enter your username and password'); ?>
        </legend>
        <?php echo $this->Form->input('username');
        echo $this->Form->input('password');
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
       $("input[value='Login").click(function(){
            $.ajax({
                url: "/cakephp/users/ajaxLogin",
                type: "POST",
                data: {
                    username: $("input[name='data[User][username]']").val(),
                    password: $("input[name='data[User][password]']").val()
                },
                success: function(response){
                    var res = JSON.parse(response);
                    if (res.status == "success") {
                        window.location.href = "/cakephp/users/index";
                    }
                }
            });
            return false;
       });
    });
</script>