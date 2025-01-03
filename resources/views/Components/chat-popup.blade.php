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
</style>



<div id="chat-popup" style="
    display: none;
    position: fixed;
    bottom: 10px;
    right: 80px;
    width: 500px;
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
        <span>Chat / Support</span>
        <button id="close-chat" style="
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 16px;
        " title="Close">×</button>
    </div>
    <!-- Main Content Area -->
    <div style="display: flex; flex-grow: 1;">
        <div id="chat-users" style="
            width: 40%;
            height: 100%;
            border-right: 1px solid #ccc;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
        ">
            <!-- Tabs for Filtering -->
            <div class="tabs" style="display: flex; justify-content: space-around;">
                <div class="tab active" data-filter="inquiry" style="
                    padding: 5px;
                    cursor: pointer;
                    flex: 1;
                    text-align: center;
                    font-weight: bold;
                ">
                    Inquiry <span>(0)</span>
                </div>
                <div class="tab" data-filter="merchant" style="
                    padding: 5px;
                    cursor: pointer;
                    flex: 1;
                    text-align: center;
                    font-weight: bold;
                ">
                    Merchant <span>(0)</span>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search-bar" style="margin-bottom: 5px;">
                <input type="text" id="search" placeholder="Search..." style="
                    width: 100%;
                    padding: 5px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    font-size:12px;
                ">
            </div>

            <!-- Placeholder for dynamically added users -->
            <div id="inquiries-container" style="display: block;">
                <div id="inquiries-list"></div>
            </div>
            <div id="merchants-container" style="display: none;">
                <div id="merchants-list"></div>
            </div>
        </div>

        <!-- Right Section: Chat Messages -->
        <div id="chat-messages-container" style="
            width: 60%;
            height: 100%;
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
                <!-- Sender's Message -->
                <div style="display: flex; justify-content: center; align-items: center; height: 100%;">
                    <div style="max-width: 70%; text-align: center;">
                        <div style="
                            padding: 10px;
                            background-color: #838383;
                            color: white;
                            border-radius: 10px;
                        ">
                            Hi Admin
                        </div>
                    </div>
                </div>

            </div>

            <!-- Chat Input -->
            <!-- Chat Input -->
            <form
                id="chat-form"
                action="{{ route('chat.sendmessages') }}"
                method="POST"
                style="padding: 10px; min-height:57px; border-top: 1px solid #ccc; display: flex; align-items: center;">
                @csrf <!-- Laravel CSRF Token -->
                <input
                    type="text"
                    id="chat-input"
                    name="message"
                    placeholder="Type your message..."
                    style="flex-grow: 1; padding: 5px; margin-right: 0px; font-size:14px;"
                >
                <input type="hidden" id="chatIdInput" name="chatId"> <!-- Hidden field for chatId -->
                <a
                    href="#"
                    id="send-buttons"
                    style="padding: 10px 10px; color: #228b22; display: flex; align-items: center; justify-content: center; background: none; border: none; cursor: pointer;">
                    <i class="fa fa-paper-plane" style="font-size: 16px;"></i>
                </a>
            </form>

    </div>
</div>
{{-- <script>
    document.getElementById("chat-button").addEventListener("click", function() {
        const chatPopup = document.getElementById("chat-popup");
        chatPopup.style.display = chatPopup.style.display === "none" ? "block" : "none";
    });

    document.getElementById("close-chat").addEventListener("click", function() {
        document.getElementById("chat-popup").style.display = "none";
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inquiriesContainer = document.getElementById('inquiries-container');
        const merchantsContainer = document.getElementById('merchants-container');
        const inquiriesListElement = document.getElementById('inquiries-list');
        const merchantsListElement = document.getElementById('merchants-list');
        const chatMessagesContainer = document.getElementById('chat-messages');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const searchInput = document.getElementById('search');
        const tabs = document.querySelectorAll('.tab');

        let selectedChatId = null;

        // Function to render inquiries list
        function renderInquiryList(inquiries) {
            inquiriesListElement.innerHTML = ''; // Clear the existing list

            inquiries.forEach(inquiry => {
                const inquiryItem = document.createElement('div');
                inquiryItem.className = 'chat-user';
                inquiryItem.style = `
                    display: flex;
                    margin-bottom: 10px;
                    cursor: pointer;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    align-items: center;
                `;
                inquiryItem.setAttribute('data-chat-id', inquiry.chat_id);

                inquiryItem.innerHTML = `
                    <img src="${inquiry.customer_avatar || 'https://via.placeholder.com/40'}" alt="Customer Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                    <div style="flex: 1; overflow: hidden;">
                        <strong style="display: block; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                            ${inquiry.customer_name || 'Unknown Customer'}
                        </strong>
                        <p style="margin: 0; font-size: 12px; color: gray; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                            ${inquiry.last_message || 'No messages yet'}
                        </p>
                    </div>
                `;

                // Add click event to fetch and display messages
                inquiryItem.addEventListener('click', async () => {
                    const chatId = inquiryItem.getAttribute('data-chat-id');
                    selectedChatId = chatId; // Update the selected chat ID

                    // Reset borders for all inquiry items and highlight the selected one
                    inquiriesListElement.querySelectorAll('.chat-user').forEach(user => {
                        user.style.border = '1px solid #ccc';
                    });
                    inquiryItem.style.border = '1px solid #228b22'; // Highlight the selected chat

                    const chatIdInput = document.getElementById('chatIdInput');
                    if (chatIdInput) {
                        chatIdInput.value = selectedChatId; // Set the selected chat ID
                    }

                    try {
                        // Fetch and render messages for the selected chat
                        const response = await fetch(`/chat/messages/${chatId}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                        const messages = await response.json();
                        renderChatMessages(messages); // Render the initial messages

                        // Start auto-refresh for this chat
                        startAutoRefresh(chatId);

                    } catch (error) {
                        console.error('Error fetching messages:', error);
                    }
                });

                inquiriesListElement.appendChild(inquiryItem);
            });

            // Function to handle auto-refresh of messages for a specific chat
            function startAutoRefresh(chatId) {
                setInterval(async () => {
                    try {
                        const refreshResponse = await fetch(`/chat/messages/${chatId}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!refreshResponse.ok) throw new Error(`HTTP error! status: ${refreshResponse.status}`);

                        const newMessages = await refreshResponse.json();
                        renderChatMessages(newMessages); // Update the chat with new messages
                    } catch (error) {
                        console.error('Error refreshing messages:', error);
                    }
                }, 3000);
            }

            // If there's a chat already selected (e.g., on page load or after a refresh), start auto-refresh immediately
            if (selectedChatId) {
                startAutoRefresh(selectedChatId);
            }
        }


        // Function to render merchants list
        function renderMerchantList(merchants) {
            merchantsListElement.innerHTML = ''; // Clear the existing list

            merchants.forEach(merchant => {
                const merchantItem = document.createElement('div');
                merchantItem.className = 'chat-user';
                merchantItem.style = `
                    display: flex;
                    margin-bottom: 10px;
                    cursor: pointer;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    align-items: center;
                `;

                merchantItem.innerHTML = `
                    <img src="${merchant.shop_img || 'https://via.placeholder.com/40'}" alt="Shop Image" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                    <div style="flex: 1; overflow: hidden;">
                        <strong style="display: block; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                            ${merchant.shop_name || 'Unknown Shop'}
                        </strong>
                        <p style="margin: 0; font-size: 12px; color: gray; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                             ${merchant.last_message || 'Tap to start conversation'}
                        </p>
                    </div>
                `;

                merchantsListElement.appendChild(merchantItem);
            });
        }

        // Function to render chat messages
        function renderChatMessages(messages) {
            chatMessagesContainer.innerHTML = ''; // Clear previous messages

            let lastMessageTime = null;
            let adminId = {{ json_encode($adminId) }};
            messages.forEach(message => {
                const isSender = message.sender_id === adminId;
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
                    // messageItem.innerHTML = `
                    //     <div style="max-width: 100%; min-width: 120px; text-align: right; margin: 5px auto;">
                    //         <div style="padding: 5px 10px; background-color: #7b4dd3; color: white; border-radius: 8px; border: 1px solid #6a3bb5; display: inline-block; font-size: 14px;">
                    //             ${message.message}
                    //         </div>
                    //         <div style="font-size: 10px; color: gray; margin-top: 5px;">
                    //             ${dateString} ${timeDisplay}
                    //         </div>
                    //     </div>
                    // `;
                    // Function to format the message into lines of up to 30 characters
                    const formatMessage = (msg, maxLength = 25) => {
                        const words = msg.split(' '); // Split the message into words
                        let line = '';
                        let formatted = '';

                        words.forEach(word => {
                            if ((line + word).length > maxLength) {
                                formatted += line.trim() + '<br>'; // Add the current line and start a new one
                                line = '';
                            }
                            line += word + ' '; // Add word to the current line
                        });

                        formatted += line.trim(); // Add any remaining text
                        return formatted;
                    };

                    const formattedMessage = formatMessage(message.message);

                    messageItem.innerHTML = `
                        <div style="max-width: 100%; min-width: 120px; text-align: right; margin: 5px auto;">
                            <div style="text-align: left; padding: 5px 10px; background-color: #7b4dd3; color: white; border-radius: 8px; border: 1px solid #6a3bb5; display: inline-block; font-size: 14px;">
                                ${formattedMessage}
                            </div>
                            <div style="font-size: 10px; color: gray; margin-top: 5px;">
                                ${dateString} ${timeDisplay}
                            </div>
                        </div>
                    `;
                } else {
                    // messageItem.innerHTML = `
                    //     <div style="display: flex; align-items: flex-start; margin: 5px auto;">
                    //         <img src="${message.sender_avatar || 'https://via.placeholder.com/40'}" alt="User Avatar"
                    //             style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; flex-shrink: 0;">
                    //         <div style="max-width: 70%; min-width: 120px;">
                    //             <div style="padding: 5px 10px; background-color: #333; color: white; border-radius: 8px; border: 1px solid #222; display: inline-block; font-size: 14px; text-align: left;">
                    //                 ${message.message}
                    //             </div>
                    //             <div style="font-size: 10px; color: gray; margin-top: 5px;">
                    //                 ${dateString} ${timeDisplay}
                    //             </div>
                    //         </div>
                    //     </div>
                    // `;
                    // Function to format the message into lines of up to 30 characters
                    const formatMessage = (msg, maxLength = 25) => {
                        const words = msg.split(' '); // Split the message into words
                        let line = '';
                        let formatted = '';

                        words.forEach(word => {
                            if ((line + word).length > maxLength) {
                                formatted += line.trim() + '<br>'; // Add the current line and start a new one
                                line = '';
                            }
                            line += word + ' '; // Add word to the current line
                        });

                        formatted += line.trim(); // Add any remaining text
                        return formatted;
                    };

                    const formattedMessage = formatMessage(message.message);

                    messageItem.innerHTML = `
                        <div style="display: flex; align-items: flex-start; margin: 5px auto;">
                            <img src="{{ asset('images/assets/bicollectionlogowname2.png') }}" alt="User Avatar"
                                style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; flex-shrink: 0; object-fit: cover;">

                            <div style="max-width: 70%; min-width: 120px;">
                                <div style="padding: 5px 10px; background-color: #333; color: white; border-radius: 8px; border: 1px solid #222; display: inline-block; font-size: 14px; text-align: left;">
                                    ${formattedMessage}
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


        // Fetch inquiries
        async function fetchInquiries() {
            try {
                const response = await fetch('/inquiries', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const inquiries = await response.json();
                renderInquiryList(inquiries); 
            } catch (error) {
                console.error('Error fetching inquiries:', error);
            }
        }

        // Fetch merchants
         async function fetchMerchants() {
            try {
                const response = await fetch('/merchantschat', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const merchants = await response.json();
                renderMerchantList(merchants);
            } catch (error) {
                console.error('Error fetching merchants:', error);
            }
        }

        // Handle tab click
        function handleTabClick(tab) {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');

            const filter = tab.getAttribute('data-filter');
            if (filter === 'inquiry') {
                inquiriesContainer.style.display = 'block';
                merchantsContainer.style.display = 'none';
                fetchInquiries();
            } else if (filter === 'merchant') {
                inquiriesContainer.style.display = 'none';
                merchantsContainer.style.display = 'block';
                fetchMerchants();
            }
        }

        // Attach event listeners to tabs
        tabs.forEach(tab => tab.addEventListener('click', () => handleTabClick(tab)));

        // Automatically activate first tab
        const activeTab = document.querySelector('.tab.active') || tabs[0];
        if (activeTab) {
            handleTabClick(activeTab);
        }

        // Search functionality
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            const users = inquiriesContainer.style.display === 'block'
                ? inquiriesListElement.querySelectorAll('.chat-user')
                : merchantsListElement.querySelectorAll('.chat-user');

            users.forEach(user => {
                const name = user.querySelector('strong').textContent.toLowerCase();
                user.style.display = name.includes(query) ? 'flex' : 'none';
            });
        });
    });
</script> --}}
<script>
    document.getElementById("chat-button").addEventListener("click", function() {
        const chatPopup = document.getElementById("chat-popup");
        chatPopup.style.display = chatPopup.style.display === "none" ? "block" : "none";
    });

    document.getElementById("close-chat").addEventListener("click", function() {
        document.getElementById("chat-popup").style.display = "none";
    });

</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inquiriesContainer = document.getElementById('inquiries-container');
        const merchantsContainer = document.getElementById('merchants-container');
        const inquiriesListElement = document.getElementById('inquiries-list');
        const merchantsListElement = document.getElementById('merchants-list');
        const chatMessagesContainer = document.getElementById('chat-messages');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const searchInput = document.getElementById('search');
        const tabs = document.querySelectorAll('.tab');

        let selectedChatId = null;

        // Function to render inquiries list
        function renderInquiryList(inquiries) {
            inquiriesListElement.innerHTML = ''; // Clear the existing list

            inquiries.forEach(inquiry => {
                const inquiryItem = document.createElement('div');
                inquiryItem.className = 'chat-user';
                inquiryItem.style = `
                    display: flex;
                    margin-bottom: 10px;
                    cursor: pointer;
                    padding: 10px;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    background-color: #fff;
                    align-items: center;
                `;
                inquiryItem.setAttribute('data-chat-id', inquiry.chat_id);

                inquiryItem.innerHTML = `
                    <img src="${inquiry.customer_avatar || 'https://via.placeholder.com/40'}" alt="Customer Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                    <div style="flex: 1; overflow: hidden;">
                        <strong style="display: block; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                            ${inquiry.customer_name || 'Unknown Customer'}
                        </strong>
                        <p style="margin: 0; font-size: 12px; color: gray; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                            ${inquiry.last_message || 'No messages yet'}
                        </p>
                    </div>
                `;

                // Add click event to fetch and display messages
                inquiryItem.addEventListener('click', async () => {
                    const chatId = inquiryItem.getAttribute('data-chat-id');
                    selectedChatId = chatId; // Update the selected chat ID

                    // Reset borders for all inquiry items and highlight the selected one
                    inquiriesListElement.querySelectorAll('.chat-user').forEach(user => {
                        user.style.border = '1px solid #ccc';
                    });
                    inquiryItem.style.border = '1px solid #228b22'; // Highlight the selected chat

                    const chatIdInput = document.getElementById('chatIdInput');
                    if (chatIdInput) {
                        chatIdInput.value = selectedChatId; // Set the selected chat ID
                    }

                    try {
                        // Fetch and render messages for the selected chat
                        const response = await fetch(`/chat/messages/${chatId}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                        const messages = await response.json();
                        renderChatMessages(messages); // Render the initial messages

                        // Start auto-refresh for this chat
                        startAutoRefresh(chatId);

                    } catch (error) {
                        console.error('Error fetching messages:', error);
                    }
                });

                inquiriesListElement.appendChild(inquiryItem);
            });

            // Function to handle auto-refresh of messages for a specific chat
            function startAutoRefresh(chatId) {
                setInterval(async () => {
                    try {
                        const refreshResponse = await fetch(`/chat/messages/${chatId}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!refreshResponse.ok) throw new Error(`HTTP error! status: ${refreshResponse.status}`);

                        const newMessages = await refreshResponse.json();
                        renderChatMessages(newMessages); // Update the chat with new messages
                    } catch (error) {
                        console.error('Error refreshing messages:', error);
                    }
                }, 3000);
            }

            // If there's a chat already selected (e.g., on page load or after a refresh), start auto-refresh immediately
            if (selectedChatId) {
                startAutoRefresh(selectedChatId);
            }
        }

        // Function to render chat messages for the selected chat_id
        function renderChatMessages(messages) {
            chatMessagesContainer.innerHTML = ''; // Clear previous messages

            let lastMessageTime = null;
            messages.forEach(message => {
                const messageItem = document.createElement('div');
                const messageDate = new Date(message.created_at);
                const timeString = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                
                messageItem.innerHTML = `
                    <div style="padding: 5px 10px; background-color: #333; color: white; border-radius: 8px; margin: 5px auto; font-size: 14px;">
                        ${message.message}
                    </div>
                    <div style="font-size: 10px; color: gray; text-align: right;">
                        ${timeString}
                    </div>
                `;
                
                chatMessagesContainer.appendChild(messageItem);
            });
        }

        // Fetch inquiries
        async function fetchInquiries() {
            try {
                const response = await fetch('/inquiries', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const inquiries = await response.json();
                renderInquiryList(inquiries); 
            } catch (error) {
                console.error('Error fetching inquiries:', error);
            }
        }

        // Fetch merchants
        async function fetchMerchants() {
            try {
                const response = await fetch('/merchantschat', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const merchants = await response.json();
                renderMerchantList(merchants);
            } catch (error) {
                console.error('Error fetching merchants:', error);
            }
        }

        fetchInquiries();
        fetchMerchants();
    });
</script>


<script>
    document.getElementById('send-buttons').addEventListener('click', function(e) {
        e.preventDefault();  // Prevent the default form submission behavior

        // Get the message and chatId values from the form
        var message = document.getElementById('chat-input').value;
        var chatId = document.getElementById('chatIdInput').value;

        // Create a FormData object for sending the form data
        var formData = new FormData();
        formData.append('message', message);
        formData.append('chatId', chatId);
        formData.append('_token', document.querySelector('input[name="_token"]').value);  // Include CSRF token

        // Send the form data via AJAX (using Fetch API)
        fetch("{{ route('chat.sendmessages') }}", {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // Assuming the backend returns JSON
        .then(data => {
            if (data.success) {
                // Handle success (for example, clear input or display a message)
                document.getElementById('chat-input').value = '';  // Clear the input field

                // Optionally, append the message to the chat display area
                var messageItem = document.createElement('div');
                messageItem.style = `
                    display: flex;
                    justify-content: flex-end;
                    align-items: flex-start;
                `;

                var now = new Date();
                var currentDate = new Date();
                var messageDate = new Date();  // The date of the message, you can set this dynamically if needed

                // Format the time (HH:mm) and compare the date
                var timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                // Check if the message date is different from the current date
                var dateString = '';
                if (currentDate.toDateString() !== messageDate.toDateString()) {
                    dateString = `
                        <div style="text-align: center; margin-top: 5px; font-size: 12px; color: gray;">
                            ---------------${messageDate.toLocaleDateString()}-------------
                        </div>
                    `;
                }

                messageItem.innerHTML = `
                    <div style="max-width: 70%; min-width: 120px; text-align: right;">
                        <div style="padding:5px 10px; background-color: #7b4dd3; color: white; border-radius: 5px; display: inline-block;">
                            ${message}
                        </div>
                        ${dateString}
                        <div id="message-time" style="font-size: 10px; color: gray; margin-top: 5px;">
                            Sent
                        </div>
                    </div>
                `;

                var chatBox = document.getElementById('chat-messages');  // The chat container element
                chatBox.appendChild(messageItem);  // Append the new message to the chat box

                // After a few seconds, update the message with the actual time
                setTimeout(() => {
                    // Find the element by its ID and replace "Sent" with the actual time
                    var timeElement = messageItem.querySelector('#message-time');
                    timeElement.innerHTML = timeString; // Update Sent with the time
                }, 2000);
            } else {
                // Handle error (e.g., show an alert)
                alert('There was an issue sending your message.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred. Please check the console for more details.');
        });
    });
</script>

