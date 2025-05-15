document.addEventListener('DOMContentLoaded', function() {
    // Global audio player
    let currentAudio = null;
    let currentTrackItem = null;
    let progressIntervals = {};

    // Function to stop any currently playing audio
    function stopCurrentAudio() {
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;

            // Clear progress interval for the track
            if (currentTrackItem) {
                const trackId = currentTrackItem.querySelector('.track-play-btn').getAttribute('data-track-id');
                if (progressIntervals[trackId]) {
                    clearInterval(progressIntervals[trackId]);
                    delete progressIntervals[trackId];
                }

                // Reset progress display
                const progressContainer = currentTrackItem.querySelector('.track-progress-container');
                if (progressContainer) {
                    const progressBar = progressContainer.querySelector('.track-progress-bar');
                    if (progressBar) {
                        progressBar.style.width = '0%';
                    }
                }

                // Change icon back to play
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

    // Function to format seconds to MM:SS
    function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs < 10 ? '0' + secs : secs}`;
    }

    // Function to play a track
    function playTrack(trackItem) {
        const playBtn = trackItem.querySelector('.track-play-btn');
        const trackId = playBtn.getAttribute('data-track-id');
        const trackUrl = playBtn.getAttribute('data-track-url');
        const playIcon = playBtn.querySelector('i');

        // If this is already playing, pause it
        if (currentAudio && currentTrackItem === trackItem) {
            stopCurrentAudio();
            return;
        }

        // Stop any currently playing audio
        stopCurrentAudio();

        // Create new audio element
        currentAudio = new Audio(trackUrl);
        currentTrackItem = trackItem;

        // Change icon to pause
        playIcon.classList.remove('fa-play');
        playIcon.classList.add('fa-pause');

        // Play the audio
        currentAudio.play().catch(error => {
            console.error('Error playing audio:', error);
            // Reset on error
            stopCurrentAudio();
        });

        // Create or update progress element
        let progressContainer = trackItem.querySelector('.track-progress-container');
        if (!progressContainer) {
            // Create progress container
            progressContainer = document.createElement('div');
            progressContainer.className = 'track-progress-container';

            // Create progress bar
            const progressBar = document.createElement('div');
            progressBar.className = 'track-progress-bar';
            progressContainer.appendChild(progressBar);

            const trackTitle = trackItem.querySelector('.track-title');
            trackTitle.appendChild(progressContainer);
        }

        const progressBar = progressContainer.querySelector('.track-progress-bar');

        // Update progress regularly
        progressIntervals[trackId] = setInterval(() => {
            if (currentAudio && !currentAudio.paused) {
                const currentTime = currentAudio.currentTime;
                const duration = currentAudio.duration || 0;
                const progressPercent = (currentTime / duration) * 100;

                if (progressBar) {
                    progressBar.style.width = `${progressPercent}%`;
                }
            }
        }, 250); // Update more frequently for smoother progress

        // Set up ended event handler
        currentAudio.addEventListener('ended', function() {
            stopCurrentAudio();
        });
    }

    // Handle play album button
    const playAlbumButton = document.getElementById('playAlbumButton');
    if (playAlbumButton) {
        playAlbumButton.addEventListener('click', function() {
            // Get first track and play it
            const firstTrackItem = document.querySelector('.track-item');
            if (firstTrackItem) {
                playTrack(firstTrackItem);
            }
        });
    }

    // Handle individual track play buttons
    const trackPlayButtons = document.querySelectorAll('.track-play-btn');
    trackPlayButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();  // Prevent bubbling to parent elements
            const trackItem = this.closest('.track-item');
            playTrack(trackItem);
        });
    });

    // Share button functionality
    const shareButton = document.getElementById('shareButton');
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            const albumTitle = document.querySelector('.album-details h1').textContent;
            const artistName = document.querySelector('.album-details a').textContent.trim();
            const currentUrl = window.location.href;

            // Check if Web Share API is available
            if (navigator.share) {
                navigator.share({
                    title: `${albumTitle} by ${artistName}`,
                    text: `Check out this album: ${albumTitle} by ${artistName}`,
                    url: currentUrl
                }).catch(error => console.log('Error sharing:', error));
            } else {
                // Fallback for browsers that don't support the Web Share API
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = currentUrl;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                alert('Album link copied to clipboard!');
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


    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-toggle') && !e.target.closest('.dropdown-menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

    // Fix for click on track items (avoid accidental clicks on dropdowns)
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

    // Add click handler for more albums section
    document.querySelectorAll('.card.card-plain').forEach(card => {
        card.addEventListener('click', function() {
            // Find the album ID from the URL structure or data attribute
            // Assuming the album ID is in card class or can be extracted from some attribute
            const albumId = this.getAttribute('data-album-id');
            if (albumId) {
                window.location.href = `/album/${albumId}`;
            }
        });
    });

    // Add click handlers for the dropdown items
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation();

            // Hide the dropdown
            const dropdownMenu = this.closest('.dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.style.display = 'none';
            }

            const trackId = this.getAttribute('data-track-id');

            // Handle add to playlist
            if (this.textContent.includes('Add to playlist')) {
                console.log(`Adding track ${trackId} to playlist`);
                // Here you would show a modal with playlist options
                alert('Add to playlist feature will be implemented soon');
            }

            // Handle share track
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

                    alert('Track link copied to clipboard!');
                }
            }
        });
    });
});