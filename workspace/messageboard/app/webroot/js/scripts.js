$(document).ready(function(){

    //login js codes

    $(document).on('submit','#loginForm',function(e){

         e.preventDefault();
         $.ajax({
             url: "/messageboard/users/ajaxLogin",
             type: "POST",
             data: {
                 email: $("input[name='data[User][email]']").val(),
                 password: $("input[name='data[User][password]']").val()
             },
             success: function(response){
                 var response = JSON.parse(response);
                 if (response.status) {
                     window.location.href = "/messageboard/home/messages";
                 }
                 $(`#message`).html(
                     `<h6 class="${response.status?`text-success`:`text-danger`}">${response.message}</h6>`
                 ).fadeIn();
             }
         });
         return false;

    });

    $(document).ready(function() {
        $('#newMessageUserId').select2();
    });

    function sendAjax(param = {}, callback) {
        $.ajax({
            url: param.url,
            type: "POST",
            data: param.data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                callback(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                callback(`Error sending message: ${textStatus} - ${errorThrown}`);
            }
        });
    }
    

    // message reply codes

    $(document).on("submit", "#UserMessagesForm", function(e){

        e.preventDefault();

        const FORM = $(this);
        const DATA = new FormData($(FORM)[0]);

        const PARAM = {url:BASE_URL+'Home/replyMessage', data:DATA};

        sendAjax(PARAM, function(response){

            if(response.success){
                $("#thread-messages").prepend(
                    response.html
                ).children().first().hide().fadeIn();

                $(FORM)[0].reset();
                setTimeout(function(){
                    elipMessage();
                    if($(".message-box").length >= 10){
                        $(".message-box:last").last().remove();

                        if(!$(".show-messages").length){
                            $(".message-box:last").parent().append(`
                            <div class="text-center">
                                <button class="btn btn-secondary show-messages">Show More</button>
                            </div>`);
                        }
                    }
                }, 200);
                
            }else{
                snackBar(response.message);
            }
        });

    });

    $('#profile_pic').on('change', function() {

        $('#error-message').hide();

        var fileInput = $(this);
        var filePath = fileInput.val();

        var fileExtension = filePath.substring(filePath.lastIndexOf('.') + 1).toLowerCase();

        var validExtensions = ['gif', 'png', 'jpg', 'jpeg'];
        if ($.inArray(fileExtension, validExtensions) === -1) {

            $('#error-message').text('Invalid file type. Please select a GIF, PNG, or JPG image.').show();
            fileInput.val(''); 
            return; 
        }

        var file = fileInput[0].files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profile-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file); 

        }
    });

    $(document).on("submit", "#updateProfileForm", function(e){

        e.preventDefault();
        
        const SEL = $(this);
        const DATA = new FormData($(SEL)[0]);
        const PARAM = {url:BASE_URL+'Profile/update', data:DATA};

        sendAjax(PARAM, function(response){
            console.log(response);

            if(response.success){
                $("#messageBoxContainer").prepend(
                    response.html
                ).children().first().hide().fadeIn();

                snackBar(`Your profile has been updated successfully.`);
                // $("#update-response").html(`Your profile has been updated successfully.`).addClass("text-success text-center").fadeIn();

                // setTimeout(function(){
                //     $("#update-response").fadeOut();
                // }, 2000);
                // $(FORM)[0].reset();

                $
            }
        });



    });

    let searchTimeout; 
    let searchKey ='';
    let messagesLimit = 10;

    $(document).on('input change', '#search-message-field', function() {
        clearTimeout(searchTimeout);

        searchKey = $(this).val().trim();
        messagesLimit = 10;
    
        searchTimeout = setTimeout(function() {
            const FORMDATA = new FormData();
            let url;
    
            if (searchKey) { 
                url = BASE_URL + `home/renderThreads/${messagesLimit}/${searchKey}`;
            } else {
                url = BASE_URL + `home/renderThreads/${messagesLimit}`;
            }
    
            const PARAM = {url: url, data: FORMDATA};
    
            getMessages(PARAM);

        }, 400);
    });

    if($("#myMessages").length){
        renderThreads();
    }

    function getMessages(PARAM){

        sendAjax(PARAM, function(response){
            console.log(response);

            if (response.success) {
                $("#myMessages").html(response.html);
            } else {

                if(response.count >= 0 && searchKey == ''){
                    $("#myMessages").html(` <div class="container mt-5">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-6">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="card-title">No Messages Yet</h5>
                                                                <p class="card-text">It looks like you haven't started a conversation yet.</p>
                                                                <!-- <a href="#" class="btn btn-primary">Start a Conversation</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>`);
                }else{
                    $("#myMessages").html(`
                                            <div class="container mt-5">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-6">
                                                        <div class="card text-center">
                                                            <div class="card-body">
                                                                <h5 class="card-title">No Messages Found</h5>
                                                                <p class="card-text">It looks like no results matched your search.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                    `);
                }
            }

            if(response.count >= 10){
                $("#myMessages").append(`<a class="btn btn-secondary show-more-messages">Show More</a>`);
            }
        });

    }

    function renderThreads(DATA = {}){

        const PARAM = {url:BASE_URL+'home/renderThreads', data:DATA};
        getMessages(PARAM);
    }

    $(document).on("click", ".show-more-messages", function(){

        $(this).remove();

        messagesLimit = messagesLimit+10;

        if(searchKey != ''){
            url = BASE_URL + `home/renderThreads/${messagesLimit}/${searchKey}`;
        }else{
            url = BASE_URL + `home/renderThreads/${messagesLimit}`;
        }

        const FORMDATA = new FormData();

        const PARAM = {url: url, data: FORMDATA};

        paginateMessages(PARAM);

    });

    function paginateMessages(PARAM){

        sendAjax(PARAM, function(response){

            if($(".no-message-found").length){
                $(".no-message-found").remove();
            }

            if (response.success) {
                $("#myMessages").append(response.html);
            } else {
     
                $("#myMessages").append(`
                  <div class="container mt-5 no-message-found">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">No Messages Found</h5>
                                    <p class="card-text">It looks like you have reached the end of the messages.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                `);
            }

           if(response.count > 10){
            $("#myMessages").append(`<a class="btn btn-secondary show-more-messages">Show More</a>`);
           }
            
        });

    }

    function showConfirmationPopup() {
        return new Promise((resolve) => {
            $('#popup-overlay').fadeIn(); 
    
            $('#confirm-delete').off('click').on('click', function() {
                $('#popup-overlay').fadeOut(); 
                resolve(true); 
            });
    
            $('#cancel-delete').off('click').on('click', function() {
                $('#popup-overlay').fadeOut();
                resolve(false);
            });
    
            $('#close-popup').off('click').on('click', function() {
                $('#popup-overlay').fadeOut(); 
                resolve(false);
            });
        });
    }

    // delete messages

    var currentThread = 0;

    $(document).on("click", ".delete-thread", async function() {
        const THREAD_ID = $(this).data('thread');
        currentThread = THREAD_ID;

        const PARENT = $(this).parents('.card');

        const confirmed = await showConfirmationPopup();
        
        if (confirmed) {
            const DATA = new FormData();
            DATA.append('thread_id', THREAD_ID);
            
            const PARAM = { url: BASE_URL + 'home/deletethread', data: DATA };
    
            sendAjax(PARAM, function(response) {
                if (response.success) {
                    $(PARENT).fadeOut();
                }
            });
        }
    });

    let currentMessage = 1;

    $(document).on("click", ".delete-message", async function() {
        const MESSAGE_ID = $(this).data('id');

        currentMessage = MESSAGE_ID;

        const PARENT = $(this).parents('.card');

        $("#popup-header").html(`Are you sure you want to delete this message?`);
        $("#popup-message").html(`This action cannot be undone.`);
    
        const confirmed = await showConfirmationPopup();

        if (confirmed) {
            const DATA = new FormData();
            DATA.append('ms_id', currentMessage);
            
            const PARAM = { url: BASE_URL + 'home/deleteMessage', data: DATA };
    
            sendAjax(PARAM, function(response) {
                if (response.success) {
                    $(PARENT).fadeOut();
                }
            });
        }
    });

    let isPagination = false;
    let defaultLimit = 10;

    function getThreadMessages(thread_id = 5, limit = 10){

        const DATA = new FormData();

        const PARAM = { url: `${BASE_URL}home/getThreadMessages/${thread_id}/${limit}`, data: DATA };

        sendAjax(PARAM, function(response){
            if(response.success){

                if(isPagination){
                    $("#thread-messages").append(response.html);

                    if(response.messages_count <= 10){

                        $("#thread-messages").append(`
                            <div class="container mt-5 no-message-found">
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h5 class="card-title">No Messages Found</h5>
                                                <p class="card-text">It looks like you have reached the end of the messages.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            `);
                    }

                }else{
                    $("#thread-messages").html(response.html);
                }
               
                if(response.messages_count >= 10){
                    $("#thread-messages").append(`
                    <div class="text-center">
                        <button class="btn btn-secondary show-messages">Show More</button>
                    </div>`);
                }

                elipMessage();
           
            }
        });
 
    }

    function elipMessage(){
        $('.message-content').each(function() {
            const $this = $(this);
            const fullHeight = $this.prop('scrollHeight');
            const maxHeight = parseInt($this.css('max-height'));
            if (fullHeight > maxHeight) {
                $this.siblings('.toggle-message').show();
            }
        });
    }

    if($('#thread-messages').length > 0){
        const THREAD_ID = $('#thread-messages').data('thread');
        getThreadMessages(THREAD_ID, defaultLimit);
    }

    $(document).on("click", ".show-messages", function(){

        $(this).remove();
        const THREAD_ID = $('#thread-messages').data('thread');
        defaultLimit = defaultLimit+10;
        isPagination = true;
        getThreadMessages(THREAD_ID, defaultLimit);
    });

    $(document).on('click', '.toggle-message', function(e) {
        e.preventDefault();

        const $this = $(this);
        const $message = $this.siblings('.message-content');

        if ($message.hasClass('expanded')) {
            $message.css('min-height', 'calc(1.2em * 4)').removeClass('expanded');
            $this.text('Show More');
        } else {
            $message.css('min-height', $message.prop('scrollHeight')).addClass('expanded');
            $this.text('Show Less');
        }
    });

    function snackBar(message = ''){
        const $snackbar = $("#snackbar");

        $snackbar.addClass("show").text(message);

        setTimeout(() => {
            $snackbar.removeClass("show");
        }, 3000);
    }

    if($('#message-sent').length){
        snackBar('Your message has been sent successfully.');
    }

    $('#registrationForm').on('submit', function(e) {
        let valid = true; 
        let errorMessage = ''; 

        $('.text-danger').remove();

        if ($('#UserName').val().trim() === '') {
            valid = false;
            errorMessage += '<div class="text-danger">Name is required.</div>';
        }

        // Validate Email
        if ($('#UserEmail').val().trim() === '') {
            valid = false;
            errorMessage += '<div class="text-danger">Email is required.</div>';
        }

        // Validate Password
        if ($('#UserPassword').val().trim() === '') {
            valid = false;
            errorMessage += '<div class="text-danger">Password is required.</div>';
        }

        // Validate Confirm Password
        if ($('#UserConfirmPassword').val().trim() === '') {
            valid = false;
            errorMessage += '<div class="text-danger">Confirm Password is required.</div>';
        } else if ($('#UserConfirmPassword').val() !== $('#UserPassword').val()) {
            valid = false;
            errorMessage += '<div class="text-danger">Passwords do not match.</div>';
        }

        if (!valid) {
            e.preventDefault();
            $('#message_error').append(errorMessage);
        }
    });
 });