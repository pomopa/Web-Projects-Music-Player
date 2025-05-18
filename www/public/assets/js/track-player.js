document.addEventListener('DOMContentLoaded', function() {
    const audioPlayer = document.getElementById('audioPlayer');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const playButton = document.getElementById('playButton');
    const progressBar = document.querySelector('.progress-bar');
    const progressContainer = document.querySelector('.progress-container');
    const currentTimeDisplay = document.querySelector('.current-time');
    const durationDisplay = document.querySelector('.duration');
    const shareButton = document.getElementById('shareButton');

    if (playButton && playButton.dataset.trackUrl) {
        audioPlayer.src = playButton.dataset.trackUrl;
    }

    function formatTime(seconds) {
        if (isNaN(seconds)) return "0:00";
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }

    function updateProgress() {
        if (audioPlayer.duration) {
            const percent = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            progressBar.style.width = `${percent}%`;
            currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
        }
    }

    function togglePlay() {
        if (audioPlayer.paused) {
            audioPlayer.play().catch(error => {
                console.error("Error playing audio:", error);
            });
            playPauseBtn.innerHTML = '<i class="fa fa-pause"></i>';
            playButton.innerHTML = `<i class="fa fa-pause me-1"></i> ${LANG.pause}`;
        } else {
            audioPlayer.pause();
            playPauseBtn.innerHTML = '<i class="fa fa-play"></i>';
            playButton.innerHTML = `<i class="fa fa-play me-1"></i> ${LANG.play}`;
        }
    }

    if (playPauseBtn) {
        playPauseBtn.addEventListener('click', togglePlay);
    }

    if (playButton) {
        playButton.addEventListener('click', togglePlay);
    }

    if (audioPlayer) {
        audioPlayer.addEventListener('timeupdate', updateProgress);

        audioPlayer.addEventListener('loadedmetadata', function() {
            durationDisplay.textContent = formatTime(audioPlayer.duration);
        });

        audioPlayer.addEventListener('ended', function() {
            playPauseBtn.innerHTML = '<i class="fa fa-play"></i>';
            playButton.innerHTML = '<i class="fa fa-play me-1"></i> Play';
            progressBar.style.width = '0%';
            currentTimeDisplay.textContent = '0:00';
        });

        audioPlayer.addEventListener('error', function(e) {
            console.error("Audio error:", e);
            alert(`${LANG.error_loading}`);
        });
    }

    if (progressContainer) {
        progressContainer.addEventListener('click', function(e) {
            const clickPosition = (e.offsetX / this.offsetWidth);
            const seekTime = clickPosition * audioPlayer.duration;
            if (!isNaN(seekTime)) {
                audioPlayer.currentTime = seekTime;
            }
        });
    }

    if (shareButton) {
        shareButton.addEventListener('click', function() {
            const trackId = this.getAttribute('data-track-id');
            const shareUrl = `${window.location.origin}/track?id=${trackId}`;

            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: shareUrl
                }).catch(console.error);
            } else {
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = shareUrl;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                alert(`${LANG.link}`);
            }
        });
    }


    const prevButton = document.querySelector('.control-btn:nth-child(1)');
    const nextButton = document.querySelector('.control-btn:nth-child(3)');

    if (prevButton) {
        prevButton.addEventListener('click', function () {
            audioPlayer.currentTime = 0;
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', function () {
            audioPlayer.currentTime = audioPlayer.duration;
        });
    }

    document.querySelectorAll('.playlist-add').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();

            const playlistId = this.dataset.playlistId;
            const trackId = this.closest('ul').dataset.trackId;

            fetch(`/my-playlists/${playlistId}/track/${trackId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(`${LANG.track_added}`);
                    } else {
                        alert(`${LANG.failed_to_add_track}` + data.message);
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert(`${LANG.failed_to_add_track}` + error.message);
                });
        });
    });
});