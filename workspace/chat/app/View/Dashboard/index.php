<div class="dashboard">
    <div class="chat-window">
        <!-- Chat Header -->
        <div class="chat-header">
            <h3>World - Chat</h3>
        </div>

        <!-- User Information Form (Display Name and Profile Picture) -->
         <form id="chati-message-form">
         <h6 class="text-center" for="display-name">Display Name</h6>
        <div class="chat-input">
            
            <input type="text" id="display-name" name="sender_name" placeholder="Enter your display name">
            <button type="button" id="set-display-name">Set</button>
            <!-- <label for="profile-picture">Profile Picture:</label>
            <input type="file" id="profile-picture" accept="image/*"> -->
        </div>
        <div class="chat-messages" id="chat-box">

            <div class="message sent">
                <div class="message-info">
                    <!-- <img src="path/to/sender-image.jpg" alt="Your Image" class="user-image"> -->
                    <span class="display-name">You</span>
                </div>
                <p>Hello, Alice!</p>
            </div>

            <div class="message received">
                <div class="message-info">
                    <!-- <img src="path/to/alice-image.jpg" alt="Alice's Image" class="user-image"> -->
                    <span class="display-name">Alice</span>
                </div>
                <p>Hi there! How are you?</p>
            </div>

            <div class="message sent">
                <div class="message-info">
                    <!-- <img src="path/to/sender-image.jpg" alt="Your Image" class="user-image"> -->
                    <span class="display-name">You</span>
                </div>
                <p>I'm good, thanks! And you?</p>
            </div>

        </div>

        <!-- Chat Input Form -->
        <div class="chat-input">
            <input type="text" name="message_text" placeholder="Type a message...">
            <button type="submit">Send</button>
        </div>
        </form>
    </div>
</div>
