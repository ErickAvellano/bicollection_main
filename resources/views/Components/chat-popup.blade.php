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
        <!-- Left Section: User List -->
        <div id="chat-users" style="
            width: 40%;
            height: 100%;
            border-right: 1px solid #ccc;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
        ">
            <!-- Sample User Item -->
            <div style="
                display: flex;
                margin-bottom: 10px;
                cursor: pointer;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                background-color: #fff;
            " class="chat-user">
                <img src="https://via.placeholder.com/40" alt="User Avatar" style="
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    margin-right: 10px;
                ">
                <div style="flex: 1; overflow: hidden;">
                    <strong style="display: block; font-size: 14px; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                        Merchant/User Name
                    </strong>
                    <p style="margin: 0; font-size: 12px; color: gray; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
                        Last message preview...
                    </p>
                </div>
            </div>

            <!-- Add more users dynamically here -->
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
                <!-- Receiver's Message -->
                <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                    <img src="https://via.placeholder.com/40" alt="User Avatar" style="
                        width: 20px;
                        height: 20px;
                        border-radius: 50%;
                        margin-right: 5px;
                    ">
                    <div style="max-width: 70%;">
                        <div style="
                            padding: 10px;
                            background-color: #333;
                            color: white;
                            border-radius: 10px;
                            text-align: left;
                        ">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </div>
                        <div style="font-size: 12px; color: gray; margin-top: 5px;">12/28/2024, 10:00 AM</div>
                    </div>
                </div>

                <!-- Sender's Message -->
                <div style="display: flex; justify-content: flex-end; align-items: flex-start; margin-bottom: 15px;">
                    <div style="max-width: 70%; text-align: right;">
                        <div style="
                            padding: 10px;
                            background-color: #7b4dd3;
                            color: white;
                            border-radius: 10px;
                        ">
                             Lorem Ipsum is simply dummy text of the printing and typesetting
                        </div>
                        <div style="font-size: 12px; color: gray; margin-top: 5px;">12/28/2024, 10:05 AM</div>
                    </div>
                </div>
            </div>
            <!-- Chat Input -->
            <div style="
                padding: 10px;
                border-top: 1px solid #ccc;
                display: flex;
                align-items: center;
            ">
            <input type="text" id="chat-input" placeholder="Type your message..." style="flex-grow: 1; padding: 5px; margin-right: 5px;">
            <a class="btn btn-outline-custom" id="send-button" href="#" style="
                padding: 10px 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
            ">
                <i class="fa fa-paper-plane" style="font-size: 16px;"></i>
            </a>

        </div>
    </div>
</div>
