/**
 * LSpoty Track Player JavaScript
 * Handles audio playback, progress tracking, and playlist management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Audio player elements
    const audioPlayer = document.getElementById('audioPlayer');
    const playPauseBtn = document.getElementById('playPauseBtn');
    const playButton = document.getElementById('playButton');
    const progressBar = document.querySelector('.progress-bar');
    const progressContainer = document.querySelector('.progress-container');
    const currentTimeDisplay = document.querySelector('.current-time');
    const durationDisplay = document.querySelector('.duration');
    const likeButton = document.getElementById('likeButton');
    const shareButton = document.getElementById('shareButton');

    // Initialize the audio player with the track URL from the play button
    if (playButton && playButton.dataset.trackUrl) {
        audioPlayer.src = playButton.dataset.trackUrl;
    }

    // Format time in MM:SS
    function formatTime(seconds) {
        if (isNaN(seconds)) return "0:00";
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
    }

    // Update progress bar
    function updateProgress() {
        if (audioPlayer.duration) {
            const percent = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            progressBar.style.width = `${percent}%`;
            currentTimeDisplay.textContent = formatTime(audioPlayer.currentTime);
        }
    }

    // Play/Pause functionality
    function togglePlay() {
        if (audioPlayer.paused) {
            audioPlayer.play().catch(error => {
                console.error("Error playing audio:", error);
                // Show error message to user
                alert("Could not play the track. Please try again.");
            });
            playPauseBtn.innerHTML = '<i class="fa fa-pause"></i>';
            playButton.innerHTML = '<i class="fa fa-pause me-1"></i> Pause';
        } else {
            audioPlayer.pause();
            playPauseBtn.innerHTML = '<i class="fa fa-play"></i>';
            playButton.innerHTML = '<i class="fa fa-play me-1"></i> Play';
        }
    }

    // Set up event listeners for audio controls
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

        // Handle audio player errors
        audioPlayer.addEventListener('error', function(e) {
            console.error("Audio error:", e);
            alert("Error loading audio. Please try again later.");
        });
    }

    // Click on progress bar to seek
    if (progressContainer) {
        progressContainer.addEventListener('click', function(e) {
            const clickPosition = (e.offsetX / this.offsetWidth);
            const seekTime = clickPosition * audioPlayer.duration;
            if (!isNaN(seekTime)) {
                audioPlayer.currentTime = seekTime;
            }
        });
    }

    // Handle playlist functionality
    const playlistItems = document.querySelectorAll('.playlist-add');
    if (playlistItems.length > 0) {
        playlistItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const playlistId = this.getAttribute('data-playlist-id');
                const trackId = this.closest('.dropdown-menu').getAttribute('data-track-id');

                // Here you would make an API call to add the track to the playlist
                console.log(`Adding track ${trackId} to playlist ${playlistId}`);

                // Simulate API call response
                // In a real app, you would use fetch or axios to make this call
                setTimeout(() => {
                    alert(`Track added to ${this.textContent}`);
                }, 500);
            });
        });
    }


    // Handle share button functionality
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            const trackId = this.getAttribute('data-track-id');
            const shareUrl = `${window.location.origin}/track?id=${trackId}`;

            // Check if the Web Share API is available
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: shareUrl
                }).catch(console.error);
            } else {
                // Fallback for browsers that don't support the Web Share API
                // Create a temporary input element to copy the URL
                const tempInput = document.createElement('input');
                document.body.appendChild(tempInput);
                tempInput.value = shareUrl;
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                // Show a message to the user
                alert('Link copied to clipboard!');
            }
        });
    }

    // Handle volume control (if needed)
    // Example: You could add a volume slider and control it here

    // Additional track controls (previous, next, shuffle, repeat)
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



    // Follow artist button functionality
    const followArtistBtn = document.querySelector('.btn-outline-success');
    if (followArtistBtn) {
        followArtistBtn.addEventListener('click', function() {
            // Toggle button state
            this.classList.toggle('btn-outline-success');
            this.classList.toggle('btn-success');

            // Update text
            const isFollowing = this.classList.contains('btn-success');
            this.textContent = isFollowing ? 'Following' : 'Follow Artist';

            // Here you would make an API call to follow/unfollow the artist
            console.log(`Artist is now ${isFollowing ? 'followed' : 'unfollowed'}`);
        });
    }
});