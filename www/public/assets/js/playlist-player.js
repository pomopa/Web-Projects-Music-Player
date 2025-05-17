document.addEventListener('DOMContentLoaded', function() {
    let currentAudio = null;
    let currentTrackItem = null;
    let progressIntervals = {};

    function stopCurrentAudio() {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;

            if (currentTrackItem) {
                const trackId = currentTrackItem.querySelector('.track-play-btn').getAttribute('data-track-id');
                if (progressIntervals[trackId]) {
                    clearInterval(progressIntervals[trackId]);
                    delete progressIntervals[trackId];
                }

                const progressContainer = currentTrackItem.querySelector('.track-progress-container');
                if (progressContainer) {
                    const progressBar = progressContainer.querySelector('.track-progress-bar');
                    if (progressBar) {
                        progressBar.style.width = '0%';
                    }
                }

                const playIcon = currentTrackItem.querySelector('.track-play-btn i');
                if (playIcon) {
                    playIcon.classList.remove('fa-pause');
                    playIcon.classList.add('fa-play');
                }
            }

            currentAudio = null;
            currentTrackItem = null;
        }
    }

    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs < 10 ? '0' + secs : secs}`;
    }

    function playTrack(trackItem) {
        const playBtn = trackItem.querySelector('.track-play-btn');
        const trackId = playBtn.getAttribute('data-track-id');
        const trackUrl = playBtn.getAttribute('data-track-url');
        const playIcon = playBtn.querySelector('i');

        if (currentAudio && currentTrackItem === trackItem) {
            stopCurrentAudio();
            return;
        }

        stopCurrentAudio();

        currentAudio = new Audio(trackUrl);
        currentTrackItem = trackItem;

        playIcon.classList.remove('fa-play');
        playIcon.classList.add('fa-pause');

        currentAudio.play().catch(error => {
            console.error('Error playing audio:', error);
            stopCurrentAudio();
        });

        let progressContainer = trackItem.querySelector('.track-progress-container');
        if (!progressContainer) {
            progressContainer = document.createElement('div');
            progressContainer.className = 'track-progress-container';

            const progressBar = document.createElement('div');
            progressBar.className = 'track-progress-bar';
            progressContainer.appendChild(progressBar);

            const trackTitle = trackItem.querySelector('.track-title');
            trackTitle.appendChild(progressContainer);
        }

        const progressBar = progressContainer.querySelector('.track-progress-bar');

        progressIntervals[trackId] = setInterval(() => {
            if (currentAudio && !currentAudio.paused) {
                const currentTime = currentAudio.currentTime;
                const duration = currentAudio.duration || 0;
                const progressPercent = (currentTime / duration) * 100;

                if (progressBar) {
                    progressBar.style.width = `${progressPercent}%`;
                }
            }
        }, 250);

        currentAudio.addEventListener('ended', function() {
            stopCurrentAudio();
        });
    }

    const playAlbumButton = document.getElementById('playPlaylistButton');
    if (playAlbumButton) {
        playAlbumButton.addEventListener('click', function() {
            const firstTrackItem = document.querySelector('.track-item');
            if (firstTrackItem) {
                playTrack(firstTrackItem);
            }
        });
    }

    const trackPlayButtons = document.querySelectorAll('.track-play-btn');
    trackPlayButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();  // Prevent bubbling to parent elements
            const trackItem = this.closest('.track-item');
            playTrack(trackItem);
        });
    });

    const shareButton = document.getElementById('shareButton');
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            const playlistTitle = document.querySelector('.playlist-details h1').textContent;
            const creatorName = document.querySelector('.playlist-details h2').textContent.trim();
            const currentUrl = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: `${playlistTitle} by ${creatorName}`,
                    text: `Check out this album: ${playlistTitle} by ${creatorName}`,
                    url: currentUrl
                }).catch(error => console.log('Error sharing:', error));
            } else {
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = currentUrl;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                alert(`${LANG.link}`);
            }
        });
    }

    document.querySelectorAll('.dropdown-toggle').forEach(dropdownToggle => {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            const dropdownMenu = this.nextElementSibling;

            // Cierra otros menús
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu !== dropdownMenu) {
                    menu.style.display = 'none';
                }
            });

            if (dropdownMenu) {
                if (dropdownMenu.style.display === 'block') {
                    dropdownMenu.style.display = 'none';
                } else {
                    dropdownMenu.style.display = 'block';

                }
            }
        });
    });


    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

    document.querySelectorAll('.track-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (!e.target.closest('.track-actions') && !e.target.closest('.dropdown-menu')) {
                const playBtn = this.querySelector('.track-play-btn');
                if (playBtn) {
                    playBtn.click();
                }
            }
        });
    });

    document.querySelectorAll('.card.card-plain').forEach(card => {
        card.addEventListener('click', function() {
            const playlistID = this.getAttribute('data-playlist-id');
            if (playlistID) {
                window.location.href = `/playlist/${playlistID}`;
            }
        });
    });

    // Add click handlers for the dropdown items
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();

            const dropdownMenu = this.closest('.dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.style.display = 'none';
            }

            const trackId = this.getAttribute('data-track-id');

            if (this.textContent.includes('Share track')) {
                const trackTitle = this.closest('.track-item').querySelector('.track-title a').textContent.trim();
                const currentUrl = `${window.location.origin}/track/${trackId}`;

                if (navigator.share) {
                    navigator.share({
                        title: trackTitle,
                        text: `Check out this track: ${trackTitle}`,
                        url: currentUrl
                    }).catch(error => console.log('Error sharing:', error));
                } else {
                    const tempInput = document.createElement('input');
                    document.body.appendChild(tempInput);
                    tempInput.value = currentUrl;
                    tempInput.select();
                    document.execCommand('copy');
                    document.body.removeChild(tempInput);

                    alert(`${LANG.link}`);
                }
            }
        });
    });

    const addPlaylistButton = document.getElementById('addPlaylistButton');
    if (addPlaylistButton) {
        addPlaylistButton.addEventListener('click', function() {
            const playlistId = window.location.pathname.split('/').pop();
            console.log(`/my-playlist/${playlistId}`)
            fetch(`/my-playlist/${playlistId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({})
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`${LANG.error_adding_playlist}`);
                    }
                    return response.json();
                })
                .then(data => {
                    alert(`${LANG.playlist_added}`);
                })
                .catch(error => {
                    console.error(error);
                    alert(`${LANG.failed_to_add_playlist}`);
                });
        });
    }
});