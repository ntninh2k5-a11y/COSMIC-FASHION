{{-- 
    Widget Chatbot AI - Cosmic Fashion
    Giao diện đồng bộ theo thiết kế 2T&C AI
--}}
<div id="cosmic-chatbot-root">
    <!-- Nút mở Chatbot (Floating Action Button) -->
    <button type="button" id="cbLauncherBtn" class="cb-launcher-btn" aria-label="Mở trò chuyện AI" title="Tư vấn thời trang với 2T&C AI">
        <i class="bi bi-chat-dots-fill"></i>
        <span class="cb-launcher-badge"></span>
    </button>

    <!-- Hộp thoại Chatbot (Popup Window) -->
    <div id="cbWidgetWindow" class="cb-widget-window" role="dialog" aria-modal="true" aria-labelledby="cbTitle">
        <!-- Header -->
        <div class="cb-header">
            <h5 id="cbTitle" class="cb-header-title">
                <span class="cb-sparkle">✨</span> 2T&C AI
            </h5>
            <div class="cb-header-actions">
                <button type="button" id="cbClearBtn" class="cb-header-btn" title="Xóa lịch sử tin nhắn" aria-label="Xóa tin nhắn">
                    <i class="bi bi-trash3"></i>
                </button>
                <button type="button" id="cbCloseBtn" class="cb-header-btn" title="Thu nhỏ khung chat" aria-label="Đóng chat">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </div>

        <!-- Thân danh sách tin nhắn -->
        <div id="cbMessagesContainer" class="cb-body">
            <!-- Tin nhắn sẽ được render động từ localStorage / JavaScript -->
        </div>

        <!-- Overlay xác nhận xóa tin nhắn -->
        <div id="cbConfirmOverlay" class="cb-confirm-overlay">
            <div class="cb-confirm-box">
                <h6>Xóa lịch sử hội thoại?</h6>
                <p>Toàn bộ tin nhắn cũ sẽ bị xóa và không thể khôi phục.</p>
                <div class="cb-confirm-btns">
                    <button type="button" id="cbBtnCancelClear" class="cb-btn-cancel">Hủy</button>
                    <button type="button" id="cbBtnConfirmClear" class="cb-btn-delete">Xóa ngay</button>
                </div>
            </div>
        </div>

        <!-- Footer nhập liệu -->
        <div class="cb-footer">
            <form id="cbChatForm" class="cb-input-form" autocomplete="off">
                <input 
                    type="text" 
                    id="cbChatInput" 
                    class="cb-input" 
                    placeholder="Bạn cần tìm sản phẩm gì?..." 
                    maxlength="1000"
                    required
                >
                <button type="submit" id="cbSendBtn" class="cb-send-btn" title="Gửi câu hỏi" aria-label="Gửi">
                    <i class="bi bi-send-fill"></i>
                </button>
            </form>
        </div>
    </div>
</div>
