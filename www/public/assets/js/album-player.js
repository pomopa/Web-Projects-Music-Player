
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

    const playAlbumButton = document.getElementById('playAlbumButton');
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
            const albumTitle = document.querySelector('.album-details h1').textContent;
            const artistName = document.querySelector('.album-details a').textContent.trim();
            const currentUrl = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: `${albumTitle} by ${artistName}`,
                    text: `Check out this album: ${albumTitle} by ${artistName}`,
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


    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
            menu.style.display = 'none';
        });
    }

    document.querySelectorAll('.dropdown-toggle').forEach(dropdownToggle => {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();

            const dropdownMenu = this.nextElementSibling;

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

    document.querySelectorAll('[id^="addPlaylistDropdown"]').forEach(addToPlaylistToggle => {
        addToPlaylistToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const playlistMenu = this.nextElementSibling;

            if (playlistMenu) {
                if (playlistMenu.style.display === 'block') {
                    playlistMenu.style.display = 'none';
                    playlistMenu.classList.remove('show');
                } else {
                    playlistMenu.style.display = 'block';
                    playlistMenu.classList.add('show');

                    if (window.innerWidth < 768) {
                        playlistMenu.style.left = '0';
                        playlistMenu.style.right = 'auto';
                        playlistMenu.style.top = '100%';
                    } else {
                        playlistMenu.style.left = '100%';
                        playlistMenu.style.top = '0';

                        const rect = playlistMenu.getBoundingClientRect();
                        if (rect.right > window.innerWidth) {
                            playlistMenu.style.left = 'auto';
                            playlistMenu.style.right = '100%';
                        }
                    }
                }
            }
        });
    });


    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
            closeAllDropdowns();
        }
    });

    document.querySelectorAll('.track-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // If the clicked element is not within the dropdown or actions area
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
            const albumId = this.getAttribute('data-album-id');
            if (albumId) {
                window.location.href = `/album/${albumId}`;
            }
        });
    });

    document.querySelectorAll('[data-track-id]').forEach(shareLink => {
        if (shareLink.textContent.includes('Share track')) {
            shareLink.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const trackId = this.getAttribute('data-track-id');
                if (!trackId) return;

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

                closeAllDropdowns();
            });
        }
    });
});