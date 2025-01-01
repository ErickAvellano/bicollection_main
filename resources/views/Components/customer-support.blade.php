<style>
    .tabs {
            display: flex;
            border-bottom: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        .tab {
            flex: 1;
            text-align: center;
            cursor: pointer;
            border-right: 1px solid #ccc;
            font-size:12px;
        }

        .tab:last-child {
            border-right: none;
        }

        .tab.active {
            background-color: #fff;
            border-bottom: 2px solid #228b22;
            font-weight: bold;
        }

        /* Styles for the search bar */
        .search-bar {
            padding: 5px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ccc;
        }

        .search-bar input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Chat user list item styling */
        .chat-user {
            display: none; /* Initially hide all items */
        }

        .chat-user.active {
            display: flex; /* Show only active filtered items */
        }
        .btn-outline-custom{
            height:40px;
        }
</style>



<div id="customer-support" style="
    display: none;
    position: fixed;
    bottom: 10px;
    right: 80px;
    width: 400px;
    height: 450px;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    z-index: 1000;
    display: flex;
    flex-direction: column;
">
    <!-- Title Bar -->
    <div style="
        background-color: #228b22;
        color: white;
        padding: 10px;
        text-align: start;
        font-size: 18px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: space-between;
    ">
        <span>Support</span>
        <button id="close-chat" style="
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
        " title="Close">×</button>
    </div>

    <!-- Right Section: Chat Messages -->
    <div id="chat-messages-container" style="
        width: 100%;
        height: 90%;
        padding: 10px;
        display: flex;
        flex-direction: column;
    ">
        <!-- Chat Messages -->
        <div id="chat-messages" style="
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: #f1f1f1;
        ">

        </div>

        <!-- Chat Input -->
        <form
            id="chat-form"
            action="{{ route('chat.sendMessageToAdmin') }}"
            method="POST"
            style="display:none; padding: 10px; min-height: 57px; border-top: 1px solid #ccc; display: flex; align-items: center;">
            @csrf <!-- Laravel CSRF Token -->
            <input
                type="text"
                id="chat-input"
                name="message"
                placeholder="Type your message..."
                style="flex-grow: 1; padding: 5px; margin-right: 0px; font-size: 14px;">
            <a
                href="#"
                id="send-buttons"
                style="padding: 10px 10px; color: #228b22; display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer;">
                <i class="fa fa-paper-plane" style="font-size: 16px;"></i>
            </a>
        </form>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerIds = {{$customerId}};
        const chatMessagesContainer = document.getElementById('chat-messages');
        let hasChat = false;
        
        // Asynchronous function to fetch chat messages
        async function fetchChatMessages() {
            try {
                const response = await fetch(`/chat/message/${customerIds}`);
                const data = await response.json(); // Parse response JSON once
                
                if (!data.success || !data.messages || data.messages.length === 0 || data.chat_id === null) {
                    displayNoChatContent(); // Call a function to handle "no chat" display
                    document.getElementById('chat-form').style.display = 'none'; // Hide chat form
                    return;
                }

                renderChatMessages(data.messages); // Pass messages to render function
                document.getElementById('chat-form').style.display = 'flex'; // Show chat form if messages exist
            } catch (error) {
                console.error("Error fetching chat messages:", error);
            }
        }
        function displayNoChatContent() {
            chatMessagesContainer.innerHTML = `
                <div style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 100%; text-align: center;">
                    <h5 class="mt-4">Hi, how can we help you?</h5>
                    <h6 class="mt-4">Select a topic:</h6>
                    <div id="problem-selection" style="
                        display: flex;
                        justify-content: center;
                        gap: 5px;
                        padding: 10px;
                        background-color: #f9f9f9;">
                        <!-- Option Buttons -->
                        <a href="#" data-customer-id="{{$customerId}}" class="btn btn-outline-custom" data-problem="Account" style="
                            padding: 5px;
                            font-size: 14px;
                            border-radius: 8px;
                            background: #228b22;
                            color: white;
                            border: none;
                            cursor: pointer;
                            height: var(--button-height, 30px);">
                            Account
                        </a>
                        <a href="#" data-customer-id="{{$customerId}}" class="btn btn-outline-custom" data-problem="Billing" style="
                            padding: 5px;
                            font-size: 14px;
                            border-radius: 8px;
                            background: #228b22;
                            color: white;
                            border: none;
                            cursor: pointer;
                            height: var(--button-height, 30px);">
                            Billing
                        </a>
                        <a href="#" data-customer-id="{{$customerId}}" class="btn btn-outline-custom" data-problem="Technical Support" style="
                            padding: 5px;
                            font-size: 14px;
                            border-radius: 8px;
                            background: #228b22;
                            color: white;
                            border: none;
                            cursor: pointer;
                            height: var(--button-height, 30px);">
                            Technical Support
                        </a>
                        <a href="#" data-customer-id="{{$customerId}}" class="btn btn-outline-custom" data-problem="Others" style="
                            padding: 5px;
                            font-size: 14px;
                            border-radius: 8px;
                            background: #228b22;
                            color: white;
                            border: none;
                            cursor: pointer;
                            height: var(--button-height, 30px);">
                            Others
                        </a>
                    </div>
                </div>
            `;
            document.getElementById('chat-form').style.display = 'none';
        }

        // Function to render chat messages
        function renderChatMessages(messages) {
            chatMessagesContainer.innerHTML = ''; // Clear previous messages

            let lastMessageTime = null; // Variable to track the time of the last message

            messages.forEach(message => {
                const isSender = message.sender_id === customerIds; // Check if the current user is the sender
                const messageItem = document.createElement('div');

                // Get current time and format it
                var now = new Date();
                var messageDate = new Date(message.created_at);
                var timeString = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                // Format the date separator if the date is different
                var dateString = '';
                if (now.toDateString() !== messageDate.toDateString()) {
                    dateString = `
                        <div style="text-align: center; margin-top: 5px; font-size: 12px; color: gray;">
                            ---------------${messageDate.toLocaleDateString()}-------------
                        </div>
                    `;
                }

                 // Check if the time is the same as the last message
                 var timeDisplay = '';
                if (!lastMessageTime || messageDate.getTime() !== lastMessageTime.getTime()) {
                    timeDisplay = `
                        <div style="font-size: 10px; color: gray; margin-top: 3px;">
                            ${timeString}
                        </div>
                    `;
                }

                // Update last message time to current message's time
                lastMessageTime = messageDate;

                if (isSender) {
                    messageItem.innerHTML = `
                        <div style="max-width: 100%; min-width: 120px; text-align: right; margin: 5px auto;">
                            <div style="padding: 5px 10px; background-color: #7b4dd3; color: white; border-radius: 8px; border: 1px solid #6a3bb5; display: inline-block; font-size: 14px;">
                                ${message.message}
                            </div>
                            <div style="font-size: 10px; color: gray; margin-top: 5px;">
                                ${dateString} ${timeDisplay}
                            </div>
                        </div>
                    `;
                } else {
                    messageItem.innerHTML = `
                        <div style="display: flex; align-items: flex-start; margin: 5px auto;">
                            <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" alt="User Avatar"
                                style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; flex-shrink: 0; object-fit: cover;">

                            <div style="max-width: 70%; min-width: 120px;">
                                <div style="padding: 5px 10px; background-color: #333; color: white; border-radius: 8px; border: 1px solid #222; display: inline-block; font-size: 14px; text-align: left;">
                                    ${message.message}
                                </div>
                                <div style="font-size: 10px; color: gray; margin-top: 5px;">
                                    ${dateString} ${timeDisplay}
                                </div>
                            </div>
                        </div>
                    `;
                }

                chatMessagesContainer.appendChild(messageItem);
            });
        }

        // Helper function to check if two Date objects represent the same time (up to minute)
        function isSameTime(lastTime, currentTime) {
            return lastTime.getFullYear() === currentTime.getFullYear() &&
                lastTime.getMonth() === currentTime.getMonth() &&
                lastTime.getDate() === currentTime.getDate() &&
                lastTime.getHours() === currentTime.getHours() &&
                lastTime.getMinutes() === currentTime.getMinutes();
        }

        // Fetch chat messages on page load
        fetchChatMessages();

        // Auto-refresh the chat messages every 5 seconds
        // setInterval(fetchChatMessages, 1000);
    });
</script>
<script>
    document.getElementById('send-buttons').addEventListener('click', function(e) {
        e.preventDefault();  // Prevent the def ault form submission behavior

        // Get the message and customerId values from the form
        var message = document.getElementById('chat-input').value;
        const customerId = {{$customerId}};

        // Create a FormData object for sending the form data
        var formData = new FormData();
        formData.append('message', message);
        formData.append('customerId', customerId);
        formData.append('_token', document.querySelector('input[name="_token"]').value);  // Include CSRF token

        // Send the form data via AJAX (using Fetch API)
        fetch("{{ route('chat.sendmessage') }}", {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                 // Clear the input field
                 document.getElementById('chat-input').value = '';
                // Append the message to the chat display area
                var chatBox = document.getElementById('chat-messages');

                // Optional: Add a date separator if it's a new day
                var lastMessageDate = chatBox.getAttribute('data-last-date');
                var currentDate = new Date().toDateString();

                if (lastMessageDate !== currentDate) {
                    var dateSeparator = document.createElement('div');
                    dateSeparator.style = `
                        text-align: center;
                        margin: 10px 0;
                        font-size: 12px;
                        color: gray;
                    `;
                    dateSeparator.innerHTML = `--- ${currentDate} ---`;
                    chatBox.appendChild(dateSeparator);

                    // Update the last date attribute
                    chatBox.setAttribute('data-last-date', currentDate);
                }

                // Create the message element
                var messageItem = document.createElement('div');
                messageItem.style = `
                    display: flex;
                    justify-content: flex-end;
                    align-items: flex-start;
                    margin-bottom: 10px;
                `;

                var now = new Date();
                var timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                messageItem.innerHTML = `
                    <div style="max-width: 70%; min-width: 120px; text-align: right;">
                        <div style="padding: 5px 10px; background-color: #7b4dd3; color: white; border-radius: 8px; display: inline-block;">
                            ${message}
                        </div>
                        <div style="font-size: 10px; color: gray; margin-top: 5px;" id="message-time">
                            Sending...
                        </div>
                    </div>
                `;

                chatBox.appendChild(messageItem);
                chatBox.scrollTop = chatBox.scrollHeight; // Scroll to the bottom

                // Simulate updating the "Sending..." status to the actual time
                setTimeout(() => {
                    var timeElement = messageItem.querySelector('#message-time');
                    timeElement.innerHTML = timeString;
                }, 2000);
            } else {
                alert('Failed to send the message. Please try again.');
            }
            })
            .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    });
</script>
<script>
   document.querySelectorAll('#problem-selection a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default link behavior

        const customerId = {{$customerId}};
        const problem = {{$customerId}};

        console.log('Customer ID:', customerId);
        console.log('Problem:', problem);

        // Send the data to the backend
        fetch('/chat/start-messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Laravel CSRF token
            },
            body: JSON.stringify({
                customerId: customerId,
                problem: problem
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Message sent successfully');
            } else {
                console.log('Error sending message:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
        });
    });
});


</script>
