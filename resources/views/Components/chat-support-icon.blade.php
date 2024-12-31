<div id="chat-support-icon" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
    <a href="#" style="text-decoration: none;" id="support-link"  data-customer-id="{{ $customerId }}">
        <div style="
            width: 50px;
            height: 50px;
            background-color: #228b22;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.06);
            animation: pulse 1.5s infinite;
        ">
            <i class="fa-solid fa-message" style="font-size: 20px;"></i>
        </div>
    </a>
</div>

<style>
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(34, 139, 34, 0.7);
        }
        100% {
            transform: scale(1);
        }
    }
</style>

