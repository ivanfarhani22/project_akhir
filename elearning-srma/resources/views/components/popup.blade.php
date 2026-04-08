<!-- Popup Modal Component -->
<div id="popup-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 9999; animation: fadeIn 0.3s ease-out;"></div>

<div id="popup-modal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); z-index: 10000; min-width: 360px; max-width: 500px; animation: slideUp 0.3s ease-out;">
    <div style="padding: 32px; text-align: center;">
        <!-- Icon -->
        <div id="popup-icon" style="font-size: 64px; margin-bottom: 24px; animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);"></div>
        
        <!-- Title -->
        <h2 id="popup-title" style="margin: 0 0 12px 0; font-size: 24px; font-weight: 700; color: #1A1A2E;"></h2>
        
        <!-- Message -->
        <p id="popup-message" style="margin: 0 0 32px 0; font-size: 15px; color: #6B7280; line-height: 1.6; white-space: pre-wrap; word-wrap: break-word;"></p>
        
        <!-- Buttons Container -->
        <div id="popup-buttons-container" style="display: flex; gap: 12px;">
            <!-- Default OK Button -->
            <button id="popup-btn" onclick="closePopup()" style="width: 100%; padding: 14px 24px; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                OK
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translate(-50%, -40%);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%);
        }
    }
    @keyframes popIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
</style>

<script>
    window.showPopup = function(type, message, title = null) {
        const overlay = document.getElementById('popup-overlay');
        const modal = document.getElementById('popup-modal');
        const icon = document.getElementById('popup-icon');
        const titleEl = document.getElementById('popup-title');
        const msgEl = document.getElementById('popup-message');
        const btn = document.getElementById('popup-btn');
        const buttonsContainer = document.getElementById('popup-buttons-container');
        
        // Reset buttons container to show only OK button
        buttonsContainer.innerHTML = '';
        
        // Determine type-specific styling
        let iconClass, btnColor, btnHover;
        switch(type) {
            case 'success':
                iconClass = 'fas fa-check-circle';
                titleEl.textContent = title || 'Berhasil!';
                btnColor = '#10B981';
                btnHover = '#059669';
                break;
            case 'error':
                iconClass = 'fas fa-times-circle';
                titleEl.textContent = title || 'Terjadi Kesalahan!';
                btnColor = '#EF4444';
                btnHover = '#DC2626';
                break;
            case 'warning':
                iconClass = 'fas fa-exclamation-circle';
                titleEl.textContent = title || 'Peringatan!';
                btnColor = '#F59E0B';
                btnHover = '#D97706';
                break;
            case 'info':
            default:
                iconClass = 'fas fa-info-circle';
                titleEl.textContent = title || 'Informasi';
                btnColor = '#3B82F6';
                btnHover = '#2563EB';
        }
        
        // Set icon and color
        icon.innerHTML = `<i class="${iconClass}" style="color: ${btnColor};"></i>`;
        icon.style.color = btnColor;
        msgEl.textContent = message;
        
        // Create OK button
        const okBtn = document.createElement('button');
        okBtn.textContent = 'OK';
        okBtn.style.cssText = `
            flex: 1; padding: 14px 24px; border: none; border-radius: 10px; 
            font-size: 15px; font-weight: 600; cursor: pointer; 
            background: ${btnColor}; color: #fff; transition: all 0.3s ease;
        `;
        okBtn.onmouseover = function() { this.style.background = btnHover; };
        okBtn.onmouseout = function() { this.style.background = btnColor; };
        okBtn.onclick = closePopup;
        
        buttonsContainer.appendChild(okBtn);
        
        // Show popup
        overlay.style.display = 'block';
        modal.style.display = 'block';
    };

    // Confirmation popup dengan Yes/No buttons
    window.showConfirmation = function(message, title = 'Konfirmasi', onYes, onNo) {
        const overlay = document.getElementById('popup-overlay');
        const modal = document.getElementById('popup-modal');
        const icon = document.getElementById('popup-icon');
        const titleEl = document.getElementById('popup-title');
        const msgEl = document.getElementById('popup-message');
        const buttonsContainer = document.getElementById('popup-buttons-container');
        
        // Set content
        icon.innerHTML = '<i class="fas fa-question-circle" style="color: #F59E0B;"></i>';
        titleEl.textContent = title;
        msgEl.textContent = message;
        
        // Clear previous buttons
        buttonsContainer.innerHTML = '';
        
        // Create Yes button
        const yesBtn = document.createElement('button');
        yesBtn.textContent = 'Ya, Lanjutkan';
        yesBtn.style.cssText = `
            flex: 1; padding: 14px 24px; border: none; border-radius: 10px; 
            font-size: 15px; font-weight: 600; cursor: pointer; 
            background: #10B981; color: #fff; transition: all 0.3s ease;
        `;
        yesBtn.onmouseover = function() { this.style.background = '#059669'; };
        yesBtn.onmouseout = function() { this.style.background = '#10B981'; };
        yesBtn.onclick = function() {
            closePopup();
            if (typeof onYes === 'function') onYes();
        };
        
        // Create No button
        const noBtn = document.createElement('button');
        noBtn.textContent = 'Tidak, Batalkan';
        noBtn.style.cssText = `
            flex: 1; padding: 14px 24px; border: none; border-radius: 10px; 
            font-size: 15px; font-weight: 600; cursor: pointer; 
            background: #EF4444; color: #fff; transition: all 0.3s ease;
        `;
        noBtn.onmouseover = function() { this.style.background = '#DC2626'; };
        noBtn.onmouseout = function() { this.style.background = '#EF4444'; };
        noBtn.onclick = function() {
            closePopup();
            if (typeof onNo === 'function') onNo();
        };
        
        buttonsContainer.appendChild(yesBtn);
        buttonsContainer.appendChild(noBtn);
        
        // Show popup
        overlay.style.display = 'block';
        modal.style.display = 'block';
    };

    window.closePopup = function() {
        const overlay = document.getElementById('popup-overlay');
        const modal = document.getElementById('popup-modal');
        overlay.style.display = 'none';
        modal.style.display = 'none';
    };

    // Close popup when clicking overlay
    document.getElementById('popup-overlay').addEventListener('click', function(e) {
        if (e.target === this) closePopup();
    });

    // Close popup with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePopup();
    });
</script>
