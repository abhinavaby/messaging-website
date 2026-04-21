<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LiveChat — Real-Time Messaging</title>
    <meta name="description"
        content="A real-time chat app. Enter your name and start chatting instantly — no login required." />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />

    <style>
        /* =====================================================
           CSS VARIABLES — Change colors here to retheme easily
           ===================================================== */
        :root {
            --bg-dark: #0d0f14;
            --bg-card: #161922;
            --bg-input: #1e2330;
            --bg-msg-self: #4f46e5;
            /* purple — your own messages */
            --bg-msg-other: #1e2330;
            /* dark — other users' messages */
            --accent: #6366f1;
            --accent-hover: #4f46e5;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border: #2a3047;
            --online-green: #22c55e;
            --radius-lg: 16px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            --font: 'Inter', sans-serif;
        }

        /* =====================================================
           RESET & BASE
           ===================================================== */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
            font-family: var(--font);
            background-color: var(--bg-dark);
            color: var(--text-primary);
            overflow: hidden;
        }

        /* =====================================================
           ANIMATED BACKGROUND GRADIENT
           ===================================================== */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(99, 102, 241, 0.12) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 80% 80%, rgba(139, 92, 246, 0.10) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* =====================================================
           NAME ENTRY SCREEN (shown first)
           ===================================================== */
        #name-screen {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            animation: fadeIn 0.4s ease;
        }

        .name-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: var(--shadow);
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .name-card .logo {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 28px;
        }

        .name-card h1 {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #f1f5f9, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .name-card p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .input-group {
            position: relative;
            margin-bottom: 16px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 18px;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: var(--font);
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .input-group input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        .input-group input::placeholder {
            color: var(--text-muted);
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: var(--radius-md);
            color: #fff;
            font-family: var(--font);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(99, 102, 241, 0.50);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .name-error {
            color: #f87171;
            font-size: 13px;
            margin-top: 10px;
            display: none;
        }

        /* =====================================================
           CHAT APP (hidden until name is entered)
           ===================================================== */
        #chat-app {
            display: none;
            height: 100vh;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        /* --- TOP HEADER BAR --- */
        .chat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            background: rgba(22, 25, 34, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .chat-header .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .header-title {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .header-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 1px;
        }

        .online-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--online-green);
            display: inline-block;
            animation: pulse 2s infinite;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 6px 14px 6px 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .btn-change-name {
            background: none;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-family: var(--font);
            font-size: 12px;
            padding: 5px 10px;
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s;
        }

        .btn-change-name:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .btn-clear {
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            border-radius: var(--radius-sm);
            color: #f87171;
            font-family: var(--font);
            font-size: 12px;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-clear:hover {
            background: rgba(248, 113, 113, 0.2);
            border-color: #f87171;
        }

        /* --- MODAL --- */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 200;
            animation: fadeIn 0.2s ease;
        }

        .modal-content {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 32px;
            width: 90%;
            max-width: 400px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .modal-content h2 {
            font-size: 20px;
            margin-bottom: 12px;
            color: #f87171;
        }

        .modal-content p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        /* --- MESSAGE AREA --- */
        #chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        #chat-messages::-webkit-scrollbar {
            width: 5px;
        }

        #chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        #chat-messages::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 99px;
        }

        #chat-messages::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }

        /* --- DATE SEPARATOR --- */
        .date-separator {
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            margin: 16px 0 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-separator::before,
        .date-separator::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* --- MESSAGE BUBBLE WRAPPER --- */
        .msg-wrapper {
            display: flex;
            flex-direction: column;
            max-width: 68%;
            animation: slideUp 0.25s ease;
        }

        .msg-wrapper.self {
            align-self: flex-end;
            align-items: flex-end;
        }

        .msg-wrapper.other {
            align-self: flex-start;
            align-items: flex-start;
        }

        /* --- SENDER NAME ABOVE BUBBLE --- */
        .msg-sender {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 4px;
            padding: 0 4px;
        }

        .msg-wrapper.self .msg-sender {
            display: none;
            /* you know it's you */
        }

        /* --- BUBBLE --- */
        .msg-bubble {
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 14.5px;
            line-height: 1.55;
            word-break: break-word;
            position: relative;
        }

        .msg-wrapper.self .msg-bubble {
            background: var(--bg-msg-self);
            color: #fff;
            border-bottom-right-radius: 5px;
        }

        .msg-wrapper.other .msg-bubble {
            background: var(--bg-msg-other);
            color: var(--text-primary);
            border: 1px solid var(--border);
            border-bottom-left-radius: 5px;
        }

        .msg-time {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            padding: 0 4px;
        }

        /* --- EMPTY STATE --- */
        #empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--text-muted);
        }

        #empty-state .empty-icon {
            font-size: 48px;
        }

        #empty-state p {
            font-size: 14px;
        }

        /* --- TYPING INDICATOR --- */
        #typing-indicator {
            padding: 0 24px 8px;
            font-size: 12px;
            color: var(--text-muted);
            min-height: 24px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .typing-dots {
            display: flex;
            gap: 3px;
        }

        .typing-dots span {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--text-muted);
            animation: typingBounce 1.2s infinite ease-in-out;
        }

        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* --- INPUT BAR --- */
        .chat-input-bar {
            padding: 16px 24px;
            background: rgba(22, 25, 34, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: flex-end;
            gap: 12px;
            flex-shrink: 0;
        }

        #msg-textarea {
            flex: 1;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            font-family: var(--font);
            font-size: 14.5px;
            padding: 12px 16px;
            resize: none;
            outline: none;
            max-height: 120px;
            line-height: 1.5;
            transition: border-color 0.2s, box-shadow 0.2s;
            overflow-y: auto;
        }

        #msg-textarea:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        #msg-textarea::placeholder {
            color: var(--text-muted);
        }

        .btn-send {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.15s, box-shadow 0.15s;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.35);
        }

        .btn-send:hover {
            transform: scale(1.07);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.50);
        }

        .btn-send:active {
            transform: scale(0.97);
        }

        .btn-send svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        /* --- STATUS BAR at bottom --- */
        .status-bar {
            text-align: center;
            font-size: 11px;
            color: var(--text-muted);
            padding: 4px;
            background: transparent;
        }

        /* =====================================================
           ANIMATIONS
           ===================================================== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.97);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        @keyframes typingBounce {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-5px);
            }
        }

        .clear-anim {
            animation: fadeOut 0.5s ease forwards !important;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-10px);
            }
        }

        /* =====================================================
           RESPONSIVE — works on mobile too
           ===================================================== */
        @media (max-width: 600px) {
            .name-card {
                padding: 36px 24px;
            }

            .chat-header {
                padding: 12px 16px;
            }

            #chat-messages {
                padding: 16px;
            }

            .chat-input-bar {
                padding: 12px 16px;
            }

            .msg-wrapper {
                max-width: 82%;
            }

            .btn-change-name {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- ============================================================
     SCREEN 1: NAME ENTRY — shown when user first opens the app
     ============================================================ -->
    <div id="name-screen">
        <div class="name-card">
            <!-- App Logo -->
            <div class="logo">💬</div>

            <h1>Welcome to LiveChat</h1>
            <p>Real-time group chat — no account needed.<br />Just enter your name to get started.</p>

            <!-- Name input field -->
            <div class="input-group">
                <input type="text" id="name-input" placeholder="Your name (e.g. Abhinav)" maxlength="50"
                    autocomplete="off" autofocus />
            </div>

            <!-- Error shown if name is empty -->
            <p class="name-error" id="name-error">⚠️ Please enter your name to continue.</p>

            <!-- Button to enter chat -->
            <button class="btn-primary" id="enter-chat-btn" onclick="enterChat()">
                Start Chatting →
            </button>
        </div>
    </div>


    <!-- ============================================================
     SCREEN 2: CHAT APP — shown after name is entered
     ============================================================ -->
    <div id="chat-app">

        <!-- TOP HEADER -->
        <div class="chat-header">
            <div class="header-left">
                <div class="header-icon">💬</div>
                <div>
                    <div class="header-title">LiveChat</div>
                    <div class="header-subtitle">
                        <span class="online-dot"></span>
                        <span id="online-count">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <!-- Shows logged-in user's name -->
                <div class="user-badge">
                    <div class="avatar" id="user-avatar">?</div>
                    <span id="user-name-display">You</span>
                </div>
                <button class="btn-clear" onclick="openClearModal()">Clear All</button>
                <button class="btn-change-name" onclick="changeName()">Change Name</button>
            </div>
        </div>

        <!-- CLEAR MESSAGES MODAL -->
        <div id="clear-modal" class="modal-overlay">
            <div class="modal-content">
                <h2>Clear All Messages?</h2>
                <p>This will permanently delete all messages for everyone. Please enter the master password to confirm.
                </p>
                <div class="input-group">
                    <input type="password" id="clear-password-input" />
                </div>
                <p id="clear-error" class="name-error" style="margin-bottom: 16px;">❌ Incorrect password</p>
                <div style="display: flex; gap: 12px;">
                    <button class="btn-change-name" style="flex: 1; padding: 12px;"
                        onclick="closeClearModal()">Cancel</button>
                    <button class="btn-primary" style="flex: 1; background: #ef4444; padding: 12px;"
                        onclick="confirmClear()">Clear All</button>
                </div>
            </div>
        </div>

        <!-- MESSAGE AREA — messages appear here -->
        <div id="chat-messages">
            <!-- Empty state shown before first message -->
            <div id="empty-state">
                <div class="empty-icon">🌐</div>
                <p>No messages yet. Say hello!</p>
            </div>
        </div>

        <!-- TYPING STATUS ROW -->
        <div id="typing-indicator"></div>

        <!-- BOTTOM INPUT BAR -->
        <div class="chat-input-bar">
            <!-- Textarea — grows as you type -->
            <textarea id="msg-textarea" placeholder="Type a message..." rows="1" maxlength="2000"
                onkeydown="handleKeyDown(event)" oninput="autoResize(this)"></textarea>

            <!-- Send button -->
            <button class="btn-send" id="send-btn" onclick="sendMessage()" title="Send (Enter)">
                <!-- Paper plane icon -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                </svg>
            </button>
        </div>

        <!-- Status bar at very bottom -->
        <div class="status-bar" id="status-bar">Connecting...</div>

    </div>


    <!-- ============================================================
     JAVASCRIPT — All the logic
     ============================================================ -->
    <script>
        // ---- STATE VARIABLES ----
        let currentUser = "";       // The name entered by user
        let lastMsgId = 0;        // Track last received message ID (for incremental fetch)
        let pollTimer = null;     // Interval reference for polling
        const POLL_MS = 1500;     // Poll every 1.5 seconds

        // ---- COLOR PALETTE for avatars (cycles through these) ----
        const COLORS = [
            "linear-gradient(135deg,#6366f1,#8b5cf6)",
            "linear-gradient(135deg,#ec4899,#f43f5e)",
            "linear-gradient(135deg,#06b6d4,#3b82f6)",
            "linear-gradient(135deg,#f59e0b,#ef4444)",
            "linear-gradient(135deg,#10b981,#06b6d4)",
            "linear-gradient(135deg,#8b5cf6,#ec4899)",
        ];

        // Get a consistent color for a username
        function getColor(name) {
            let hash = 0;
            for (let c of name) hash = c.charCodeAt(0) + hash * 31;
            return COLORS[Math.abs(hash) % COLORS.length];
        }

        // Get initials from a name (e.g., "Abhinav Kumar" → "AK")
        function getInitials(name) {
            return name.trim().split(' ')
                .map(w => w[0].toUpperCase())
                .slice(0, 2)
                .join('');
        }

        // ---- ENTER CHAT ----
        // Called when user clicks "Start Chatting"
        function enterChat() {
            const nameInput = document.getElementById('name-input');
            const name = nameInput.value.trim();

            if (!name) {
                document.getElementById('name-error').style.display = 'block';
                nameInput.focus();
                return;
            }

            // Save name
            currentUser = name;
            sessionStorage.setItem('chatUsername', name); // remember during session

            // Update UI with user's name
            document.getElementById('user-name-display').textContent = name;
            const avatarEl = document.getElementById('user-avatar');
            avatarEl.textContent = getInitials(name);
            avatarEl.style.background = getColor(name);

            // Hide name screen, show chat
            document.getElementById('name-screen').style.display = 'none';
            document.getElementById('chat-app').style.display = 'flex';

            // Load messages immediately, then start polling
            fetchMessages();
            pollTimer = setInterval(fetchMessages, POLL_MS);

            // Focus the textarea
            document.getElementById('msg-textarea').focus();
        }

        // ---- ALLOW Enter KEY to submit (Shift+Enter = new line) ----
        function handleKeyDown(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault(); // prevent newline
                sendMessage();
            }
        }

        // ---- AUTO-RESIZE textarea as user types ----
        function autoResize(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight, 120) + 'px';
        }

        // ---- SEND MESSAGE ----
        function sendMessage() {
            const textarea = document.getElementById('msg-textarea');
            const msg = textarea.value.trim();

            if (!msg || !currentUser) return;

            // Disable button while sending
            const btn = document.getElementById('send-btn');
            btn.disabled = true;
            btn.style.opacity = '0.6';

            // Use FormData to send POST request
            const formData = new FormData();
            formData.append('username', currentUser);
            formData.append('msg', msg);

            fetch('send_message.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        textarea.value = '';
                        textarea.style.height = 'auto';
                        // Immediately fetch to show own message fast
                        fetchMessages();
                    } else {
                        setStatus('❌ Failed to send. Try again.');
                    }
                })
                .catch(() => {
                    setStatus('❌ Network error. Check your connection.');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.style.opacity = '1';
                    textarea.focus();
                });
        }

        // ---- FETCH MESSAGES from server ----
        function fetchMessages() {
            // Use lastMsgId to only get NEW messages (after first load)
            const url = 'get_messages.php' + (lastMsgId > 0 ? `?last_id=${lastMsgId}` : '');

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // If server says cleared, wipe our local list
                        if (data.cleared) {
                            const container = document.getElementById('chat-messages');
                            container.innerHTML = `
                        <div id="empty-state">
                            <div class="empty-icon">🌐</div>
                            <p>No messages yet. Say hello!</p>
                        </div>`;
                            lastMsgId = 0;
                            setStatus('✨ Chat was cleared by an administrator.');
                            return;
                        }

                        const msgs = data.messages;

                        if (msgs.length === 0) {
                            if (lastMsgId === 0) {
                                // No messages at all — show empty state
                                document.getElementById('empty-state').style.display = 'flex';
                                document.getElementById('online-count').textContent = 'Be the first to chat!';
                            }
                            setStatus('✅ Connected · Listening for messages...');
                            return;
                        }

                        // Hide empty state when messages arrive
                        const emptyState = document.getElementById('empty-state');
                        if (emptyState) emptyState.style.display = 'none';

                        // Render each message
                        const container = document.getElementById('chat-messages');
                        let shouldScroll = isAtBottom(container);

                        msgs.forEach(m => {
                            appendMessage(m, container);
                            if (m.id > lastMsgId) lastMsgId = m.id; // Update tracker
                        });

                        // Scroll to bottom if user was already at bottom
                        if (shouldScroll) scrollToBottom(container);

                        // Update status count (approximate)
                        setStatus(`✅ Connected · ${lastMsgId} messages`);
                        document.getElementById('online-count').textContent = 'Live · Everyone can see this';
                    }
                })
                .catch(() => {
                    setStatus('⚠️ Connection lost. Retrying...');
                });
        }

        // ---- BUILD AND APPEND A MESSAGE BUBBLE ----
        function appendMessage(m, container) {
            const isSelf = (m.username === currentUser);

            // Create wrapper div
            const wrapper = document.createElement('div');
            wrapper.className = `msg-wrapper ${isSelf ? 'self' : 'other'}`;
            wrapper.id = `msg-${m.id}`;

            // Create sender name (only for others)
            if (!isSelf) {
                const senderEl = document.createElement('div');
                senderEl.className = 'msg-sender';
                senderEl.textContent = m.username;
                wrapper.appendChild(senderEl);
            }

            // Create bubble
            const bubble = document.createElement('div');
            bubble.className = 'msg-bubble';
            bubble.textContent = m.msg;
            wrapper.appendChild(bubble);

            // Create time
            const timeEl = document.createElement('div');
            timeEl.className = 'msg-time';
            timeEl.textContent = formatTime(m.created_at);
            wrapper.appendChild(timeEl);

            // Append to container
            container.appendChild(wrapper);
        }

        // ---- FORMAT TIME (e.g., "2:34 PM") ----
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        }

        // ---- SCROLL HELPERS ----
        function isAtBottom(el) {
            return el.scrollHeight - el.scrollTop - el.clientHeight < 50;
        }

        function scrollToBottom(el) {
            el.scrollTop = el.scrollHeight;
        }

        // ---- STATUS BAR ----
        function setStatus(text) {
            document.getElementById('status-bar').textContent = text;
        }

        // ---- CLEAR MESSAGES MODAL ----
        function openClearModal() {
            document.getElementById('clear-modal').style.display = 'flex';
            document.getElementById('clear-password-input').focus();
        }

        function closeClearModal() {
            document.getElementById('clear-modal').style.display = 'none';
            document.getElementById('clear-password-input').value = '';
            document.getElementById('clear-error').style.display = 'none';
        }

        function confirmClear() {
            const password = document.getElementById('clear-password-input').value.trim();
            if (!password) return;

            const formData = new FormData();
            formData.append('password', password);

            fetch('clear_messages.php', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                if (data.status === 'success') {
                    closeClearModal();
                    // Clear local messages
                    const container = document.getElementById('chat-messages');
                    container.innerHTML = `
                    <div id="empty-state">
                        <div class="empty-icon">🌐</div>
                        <p>No messages yet. Say hello!</p>
                    </div>`;
                    lastMsgId = 0;
                    setStatus('✨ All messages cleared.');
                } else {
                    document.getElementById('clear-error').style.display = 'block';
                }
            })
                .catch(() => {
                    setStatus('❌ Failed to clear messages.');
                });
        }

        // ---- CHANGE NAME ----
        function changeName() {
            // Reset to name screen
            document.getElementById('chat-app').style.display = 'none';
            document.getElementById('name-screen').style.display = 'flex';
            document.getElementById('name-input').value = currentUser;
            document.getElementById('name-input').focus();
            // Stop polling
            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }
        }

        // ---- ON PAGE LOAD ----
        // If user had a name in session, skip to chat
        window.addEventListener('load', () => {
            const savedName = sessionStorage.getItem('chatUsername');
            if (savedName) {
                document.getElementById('name-input').value = savedName;
                enterChat();
            }
        });
    </script>

</body>

</html>