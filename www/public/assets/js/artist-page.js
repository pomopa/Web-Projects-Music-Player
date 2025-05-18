document.addEventListener('DOMContentLoaded', function() {

    const shareButton = document.getElementById('shareButton');
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            const artistId = this.getAttribute('data-artist-id');
            const shareUrl = `${window.location.origin}/artist/${artistId}`;

            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: shareUrl
                }).then(() => {
                    console.log('Thanks for sharing!');
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(shareUrl).then(function() {
                    const toast = document.createElement('div');
                    toast.classList.add('toast-notification');
                    toast.textContent = 'Artist profile link copied to clipboard!';
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        toast.classList.add('toast-hide');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }, function() {
                    alert(`${LANG.link}`);
                });
            }
        });
    }

    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { opacity: 0.7; }
            50% { opacity: 1; }
            100% { opacity: 0.7; }
        }
        .verified-badge {
            animation: pulse 2s infinite ease-in-out;
        }
        .pulse-animation {
            animation: pulse 0.7s ease-in-out;
        }
        .toast-notification {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(29, 185, 84, 0.9);
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        .toast-hide {
            opacity: 0;
        }
    `;
    document.head.appendChild(style);
});