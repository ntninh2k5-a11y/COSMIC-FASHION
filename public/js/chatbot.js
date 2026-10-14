/**
 * Chatbot Widget Client Logic - Cosmic Fashion (2T&C AI)
 * Quản lý lưu trữ tin nhắn (LocalStorage), kết nối API và giao diện
 */

(function () {
    'use strict';

    const STORAGE_KEY = 'cosmic_chat_history_v1';
    const DEFAULT_WELCOME = 'Chào bạn! Mình là trợ lý AI của cửa hàng. Bạn đang cần tìm kiếm mẫu trang phục, size hay mức giá nào hôm nay?';

    // DOM Elements
    let widgetWindow, launcherBtn, closeBtn, clearBtn, messagesContainer, chatForm, chatInput, sendBtn;
    let confirmOverlay, btnCancelClear, btnConfirmClear;

    // Lịch sử tin nhắn lưu trữ trong RAM
    let chatHistory = [];

    // Khởi tạo khi DOM sẵn sàng
    document.addEventListener('DOMContentLoaded', () => {
        initElements();
        if (!widgetWindow) return;

        bindEvents();
        loadHistory();
    });

    function initElements() {
        widgetWindow = document.getElementById('cbWidgetWindow');
        launcherBtn = document.getElementById('cbLauncherBtn');
        closeBtn = document.getElementById('cbCloseBtn');
        clearBtn = document.getElementById('cbClearBtn');
        messagesContainer = document.getElementById('cbMessagesContainer');
        chatForm = document.getElementById('cbChatForm');
        chatInput = document.getElementById('cbChatInput');
        sendBtn = document.getElementById('cbSendBtn');

        confirmOverlay = document.getElementById('cbConfirmOverlay');
        btnCancelClear = document.getElementById('cbBtnCancelClear');
        btnConfirmClear = document.getElementById('cbBtnConfirmClear');
    }

    function bindEvents() {
        // Mở / Đóng Chatbot
        if (launcherBtn) {
            launcherBtn.addEventListener('click', toggleChat);
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', closeChat);
        }

        // Xóa lịch sử tin nhắn
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                if (confirmOverlay) {
                    confirmOverlay.classList.add('cb-show');
                } else if (confirm('Bạn có chắc chắn muốn xóa toàn bộ lịch sử tin nhắn không?')) {
                    clearAllHistory();
                }
            });
        }

        if (btnCancelClear) {
            btnCancelClear.addEventListener('click', () => {
                confirmOverlay.classList.remove('cb-show');
            });
        }

        if (btnConfirmClear) {
            btnConfirmClear.addEventListener('click', () => {
                confirmOverlay.classList.remove('cb-show');
                clearAllHistory();
            });
        }

        // Gửi tin nhắn
        if (chatForm) {
            chatForm.addEventListener('submit', (e) => {
                e.preventDefault();
                handleSendMessage();
            });
        }

        if (chatInput) {
            chatInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    handleSendMessage();
                }
            });
        }
    }

    function toggleChat() {
        if (!widgetWindow) return;
        const isOpen = widgetWindow.classList.contains('cb-open');
        if (isOpen) {
            closeChat();
        } else {
            openChat();
        }
    }

    function openChat() {
        if (!widgetWindow) return;
        widgetWindow.classList.add('cb-open');
        scrollToBottom();
        setTimeout(() => {
            if (chatInput) chatInput.focus();
        }, 200);
    }

    function closeChat() {
        if (!widgetWindow) return;
        widgetWindow.classList.remove('cb-open');
    }

    /**
     * Tải lịch sử tin nhắn từ LocalStorage
     * Đảm bảo tin nhắn cũ KHÔNG BAO GIỜ MẤT trừ khi người dùng bấm xóa
     */
    function loadHistory() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                chatHistory = JSON.parse(saved);
            }
        } catch (e) {
            console.error('Lỗi khi đọc lịch sử chat:', e);
            chatHistory = [];
        }

        renderAllMessages();
    }

    /**
     * Lưu lịch sử vào LocalStorage
     */
    function saveHistory() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(chatHistory));
        } catch (e) {
            console.error('Lỗi khi lưu lịch sử chat:', e);
        }
    }

    /**
     * Xóa sạch toàn bộ lịch sử tin nhắn
     */
    function clearAllHistory() {
        localStorage.removeItem(STORAGE_KEY);
        chatHistory = [];
        renderAllMessages();

        // Gửi thông báo đến server (tùy chọn)
        fetch('/chatbot/clear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken()
            }
        }).catch(() => {});
    }

    /**
     * Render lại toàn bộ tin nhắn lên giao diện
     */
    function renderAllMessages() {
        if (!messagesContainer) return;
        messagesContainer.innerHTML = '';

        if (!chatHistory || chatHistory.length === 0) {
            // Hiển thị tin nhắn chào mừng mặc định
            appendBotMessage(DEFAULT_WELCOME, [], false);
            return;
        }

        chatHistory.forEach((msg) => {
            if (msg.role === 'user') {
                appendUserMessage(msg.content, false);
            } else {
                appendBotMessage(msg.content, msg.products || [], false);
            }
        });

        scrollToBottom();
    }

    /**
     * Xử lý khi người dùng nhấn Gửi
     */
    async function handleSendMessage() {
        const text = chatInput ? chatInput.value.trim() : '';
        if (!text) return;

        // Xóa ô input
        chatInput.value = '';

        // Hiển thị tin nhắn người dùng lên giao diện
        appendUserMessage(text, true);

        // Lưu vào lịch sử
        chatHistory.push({ role: 'user', content: text });
        saveHistory();

        // Khóa input & hiển thị typing indicator
        setInputLoading(true);
        const typingEl = showTypingIndicator();

        try {
            // Chuẩn bị payload gửi lên server
            const historyPayload = chatHistory.slice(-8).map(m => ({
                role: m.role,
                content: m.content
            }));

            const response = await fetch('/chatbot/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({
                    message: text,
                    history: historyPayload
                })
            });

            removeTypingIndicator(typingEl);

            if (!response.ok) {
                throw new Error('Lỗi từ máy chủ: ' + response.status);
            }

            const data = await response.json();
            const reply = data.reply || 'Rất tiếc, đã có lỗi khi tạo phản hồi.';
            const products = data.products || [];

            // Hiển thị tin nhắn bot
            appendBotMessage(reply, products, true);

            // Lưu vào lịch sử
            chatHistory.push({
                role: 'assistant',
                content: reply,
                products: products
            });
            saveHistory();

        } catch (error) {
            console.error('Lỗi kết nối chatbot:', error);
            removeTypingIndicator(typingEl);
            appendBotMessage('Xin lỗi bạn, kết nối tới máy chủ AI đang bị gián đoạn. Bạn vui lòng thử lại sau nhé!', [], true);
        } finally {
            setInputLoading(false);
            if (chatInput) chatInput.focus();
        }
    }

    /**
     * Thêm tin nhắn của User vào DOM
     */
    function appendUserMessage(text, shouldScroll = true) {
        if (!messagesContainer) return;

        const msgDiv = document.createElement('div');
        msgDiv.className = 'cb-message cb-message-user';

        const bubble = document.createElement('div');
        bubble.className = 'cb-bubble';
        bubble.textContent = text;

        msgDiv.appendChild(bubble);
        messagesContainer.appendChild(msgDiv);

        if (shouldScroll) scrollToBottom();
    }

    /**
     * Thêm tin nhắn của Bot vào DOM (có format markdown nhẹ và preview card sản phẩm)
     */
    function appendBotMessage(text, products = [], shouldScroll = true) {
        if (!messagesContainer) return;

        const msgDiv = document.createElement('div');
        msgDiv.className = 'cb-message cb-message-bot';

        const bubble = document.createElement('div');
        bubble.className = 'cb-bubble';
        bubble.innerHTML = formatMarkdown(text);

        // Nếu có sản phẩm gợi ý đi kèm
        if (products && products.length > 0) {
            const previewWrap = document.createElement('div');
            previewWrap.className = 'cb-products-preview';

            products.forEach(p => {
                const card = document.createElement('a');
                card.className = 'cb-product-card';
                card.href = p.url;
                card.target = '_blank';

                const imgHtml = p.image_url 
                    ? `<img src="${p.image_url}" alt="${escapeHtml(p.name)}" onerror="this.src='/img_react/nam1.jpg'">`
                    : `<div style="width:44px;height:44px;border-radius:8px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;"><i class="bi bi-bag text-muted"></i></div>`;

                card.innerHTML = `
                    ${imgHtml}
                    <div class="cb-product-info">
                        <div class="cb-product-name">${escapeHtml(p.name)}</div>
                        <div class="cb-product-price">${p.price_formatted}</div>
                    </div>
                    <i class="bi bi-chevron-right text-muted" style="font-size:0.75rem;"></i>
                `;
                previewWrap.appendChild(card);
            });

            bubble.appendChild(previewWrap);
        }

        msgDiv.appendChild(bubble);
        messagesContainer.appendChild(msgDiv);

        if (shouldScroll) scrollToBottom();
    }

    /**
     * Hiển thị Typing Indicator
     */
    function showTypingIndicator() {
        if (!messagesContainer) return null;

        const msgDiv = document.createElement('div');
        msgDiv.className = 'cb-message cb-message-bot cb-typing-wrapper';

        const indicator = document.createElement('div');
        indicator.className = 'cb-typing-indicator';
        indicator.innerHTML = `
            <div class="cb-typing-dot"></div>
            <div class="cb-typing-dot"></div>
            <div class="cb-typing-dot"></div>
        `;

        msgDiv.appendChild(indicator);
        messagesContainer.appendChild(msgDiv);
        scrollToBottom();

        return msgDiv;
    }

    function removeTypingIndicator(el) {
        if (el && el.parentNode) {
            el.parentNode.removeChild(el);
        }
    }

    /**
     * Quản lý trạng thái đang tải của ô nhập liệu
     */
    function setInputLoading(isLoading) {
        if (chatInput) chatInput.disabled = isLoading;
        if (sendBtn) sendBtn.disabled = isLoading;
    }

    function scrollToBottom() {
        if (!messagesContainer) return;
        requestAnimationFrame(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
    }

    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * Chuyển đổi Markdown đơn giản sang HTML an toàn
     */
    function formatMarkdown(text) {
        if (!text) return '';

        let html = escapeHtml(text);

        // In đậm: **text** hoặc __text__
        html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

        // Loại bỏ đoạn link bị cắt dở ở cuối (nếu câu trả lời bị ngắt giữa chừng)
        html = html.replace(/\[[^\]]*$/g, '');
        html = html.replace(/\[[^\]]+\]\([^\)]*$/g, '');

        // Đường dẫn markdown: [label](url)
        html = html.replace(/\[([^\]]+)\]\((https?:\/\/[^\s\)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1 <i class="bi bi-box-arrow-up-right" style="font-size:0.75rem;"></i></a>');
        html = html.replace(/\[([^\]]+)\]\((\/[^\s\)]+)\)/g, '<a href="$2" target="_blank">$1 <i class="bi bi-box-arrow-up-right" style="font-size:0.75rem;"></i></a>');

        // Gạch đầu dòng: - hoặc *
        const lines = html.split('\n');
        let inList = false;
        let result = [];

        lines.forEach(line => {
            const trimmed = line.trim();
            if (trimmed.startsWith('- ') || trimmed.startsWith('* ')) {
                if (!inList) {
                    result.push('<ul>');
                    inList = true;
                }
                result.push(`<li>${trimmed.substring(2)}</li>`);
            } else {
                if (inList) {
                    result.push('</ul>');
                    inList = false;
                }
                if (trimmed.length > 0) {
                    result.push(`<p>${line}</p>`);
                }
            }
        });

        if (inList) {
            result.push('</ul>');
        }

        return result.join('');
    }

    function escapeHtml(string) {
        const entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return String(string).replace(/[&<>"']/g, function (s) {
            return entityMap[s];
        });
    }

})();
