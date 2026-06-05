<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>502 - Bad Gateway | MagangApp</title>
    @vite(['resources/css/app.css'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background animated shapes */
        .bg-shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
            z-index: 0;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 500px;
            height: 500px;
            bottom: -250px;
            right: -250px;
            animation-delay: 5s;
            animation-duration: 25s;
        }

        .shape-3 {
            width: 200px;
            height: 200px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 10s;
            animation-duration: 15s;
            opacity: 0.3;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -30px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }

        /* Main container */
        .error-container {
            position: relative;
            z-index: 1;
            max-width: 600px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Error code */
        .error-code {
            font-size: 120px;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1;
            margin-bottom: 20px;
            letter-spacing: -5px;
        }

        /* Server icon animation */
        .server-icon {
            margin-bottom: 30px;
            position: relative;
            display: inline-block;
        }

        .server-icon svg {
            width: 100px;
            height: 100px;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
        }

        .signal-wave {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(0.8);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0;
            }
        }

        /* Title */
        .error-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }

        /* Message */
        .error-message {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        /* Divider */
        .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            margin: 25px auto;
            border-radius: 3px;
        }

        /* Solutions box */
        .solutions-box {
            background: #f3f4f6;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .solutions-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .solutions-list {
            list-style: none;
            padding-left: 0;
        }

        .solutions-list li {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
            padding-left: 24px;
            position: relative;
        }

        .solutions-list li:before {
            content: "•";
            color: #667eea;
            font-weight: bold;
            font-size: 16px;
            position: absolute;
            left: 8px;
        }

        /* Button group */
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        /* Loading spinner for retry button */
        .loading-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-retrying .loading-spinner {
            display: inline-block;
        }

        .btn-retrying .btn-text {
            display: none;
        }

        /* Footer */
        .error-footer {
            margin-top: 30px;
            font-size: 12px;
            color: #9ca3af;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .error-container {
                padding: 35px 25px;
            }

            .error-code {
                font-size: 80px;
            }

            .error-title {
                font-size: 22px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>
    <div class="bg-shape shape-3"></div>

    <div class="error-container">
        <div class="server-icon">
            <div class="signal-wave"></div>
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 4H20C21.1 4 22 4.9 22 6V18C22 19.1 21.1 20 20 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="white"/>
                <path d="M8 12H16" stroke="#667eea" stroke-width="2" stroke-linecap="round"/>
                <path d="M12 8V16" stroke="#667eea" stroke-width="2" stroke-linecap="round"/>
                <circle cx="12" cy="12" r="2" fill="#667eea"/>
                <path d="M6 16H18" stroke="#764ba2" stroke-width="2" stroke-linecap="round"/>
                <path d="M6 8H18" stroke="#764ba2" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>

        <div class="error-code">502</div>
        <h1 class="error-title">Bad Gateway</h1>
        <p class="error-message">
            Maaf, terjadi kesalahan komunikasi antara server. Tim teknis kami sedang bekerja untuk memperbaiki masalah ini.
        </p>

        <div class="divider"></div>

        <div class="solutions-box">
            <div class="solutions-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#667eea" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M12 16v-4M12 8h.01"/>
                </svg>
                Yang bisa Anda lakukan:
            </div>
            <ul class="solutions-list">
                <li>Refresh halaman dalam beberapa menit</li>
                <li>Periksa koneksi internet Anda</li>
                <li>Bersihkan cache browser</li>
                <li>Hubungi tim dukungan jika masalah berlanjut</li>
            </ul>
        </div>

        <div class="button-group">
            <a href="javascript:void(0)" onclick="refreshPage()" class="btn btn-primary" id="refreshBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M23 4v6h-6M1 20v-6h6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="btn-text">Refresh Halaman</span>
                <span class="loading-spinner"></span>
            </a>
            <a href="/" class="btn btn-secondary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2h-5v-8H7v8H5a2 2 0 0 1-2-2z"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="error-footer">
            <span>⏱️ Server sedang sibuk | 🔄 Coba lagi nanti</span>
        </div>
    </div>

    <script>
        // Refresh page function with loading state
        function refreshPage() {
            const btn = document.getElementById('refreshBtn');
            btn.classList.add('btn-retrying');
            
            setTimeout(() => {
                window.location.reload();
            }, 500);
        }

        // Auto retry countdown (optional)
        let countdown = 30;
        const countdownElement = document.createElement('div');
        countdownElement.style.marginTop = '15px';
        countdownElement.style.fontSize = '12px';
        countdownElement.style.color = '#9ca3af';
        countdownElement.innerHTML = `⏳ Mencoba ulang otomatis dalam <span id="countdown">${countdown}</span> detik`;
        
        const container = document.querySelector('.error-container');
        const footer = document.querySelector('.error-footer');
        container.insertBefore(countdownElement, footer);
        
        const countdownInterval = setInterval(() => {
            countdown--;
            const countdownSpan = document.getElementById('countdown');
            if (countdownSpan) {
                countdownSpan.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                refreshPage();
            }
        }, 1000);
    </script>
</body>
</html>