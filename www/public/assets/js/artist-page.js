document.addEventListener('DOMContentLoaded', function() {
    // Handle Follow button
    const followButton = document.getElementById('followButton');
    if (followButton) {
        followButton.addEventListener('click', function() {
            const artistId = this.getAttribute('data-artist-id');

            // Toggle follow status
            if (this.classList.contains('followed')) {
                this.classList.remove('followed');
                this.classList.remove('btn-success');
                this.innerHTML = '<i class="fa fa-user-plus me-1"></i> Follow';

                // Make AJAX call to unfollow
                toggleFollowStatus(artistId, 'unfollow');
            } else {
                this.classList.add('followed');
                this.classList.add('btn-success');
                this.innerHTML = '<i class="fa fa-check me-1"></i> Following';

                // Make AJAX call to follow
                toggleFollowStatus(artistId, 'follow');
            }
        });
    }

    // Toggle follow status via AJAX
    function toggleFollowStatus(artistId, action) {
        fetch('/artist/toggleFollow', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: `artist_id=${artistId}`
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(`Artist ${artistId} ${data.status}`);
                } else {
                    console.error('Error:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Handle Share button
    const shareButton = document.getElementById('shareButton');
    if (shareButton) {
        shareButton.addEventListener('click', function() {
            const artistId = this.getAttribute('data-artist-id');
            const shareUrl = `${window.location.origin}/artist/${artistId}`;

            // Check if Web Share API is supported
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: shareUrl
                }).then(() => {
                    console.log('Thanks for sharing!');
                }).catch(console.error);
            } else {
                // Fallback - copy to clipboard
                navigator.clipboard.writeText(shareUrl).then(function() {
                    // Create a temporary toast notification
                    const toast = document.createElement('div');
                    toast.classList.add('toast-notification');
                    toast.textContent = 'Artist profile link copied to clipboard!';
                    document.body.appendChild(toast);

                    // Remove the toast after 3 seconds
                    setTimeout(() => {
                        toast.classList.add('toast-hide');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }, function() {
                    alert('Could not copy the link. Please try again.');
                });
            }
        });
    }

    // Track play functionality
    const audioPlayer = document.getElementById('audioPlayer');
    const trackItems = document.querySelectorAll('.track-item');
    let currentlyPlaying = null;

    trackItems.forEach(track => {
        const playButton = track.querySelector('.play-track-btn');

        if (playButton) {
            playButton.addEventListener('click', function() {
                const trackUrl = track.getAttribute('data-track-url');

                if (!trackUrl) {
                    console.error('No audio URL available for this track');
                    return;
                }

                // If this is the currently playing track, toggle play/pause
                if (currentlyPlaying === track) {
                    if (audioPlayer.paused) {
                        audioPlayer.play();
                        this.innerHTML = '<i class="fa fa-pause"></i>';
                    } else {
                        audioPlayer.pause();
                        this.innerHTML = '<i class="fa fa-play"></i>';
                    }
                } else {
                    // Reset previous playing track if any
                    if (currentlyPlaying) {
                        const prevButton = currentlyPlaying.querySelector('.play-track-btn');
                        if (prevButton) {
                            prevButton.innerHTML = '<i class="fa fa-play"></i>';
                        }
                    }

                    // Set up new track
                    audioPlayer.src = trackUrl;
                    audioPlayer.play()
                        .then(() => {
                            this.innerHTML = '<i class="fa fa-pause"></i>';
                            currentlyPlaying = track;
                        })
                        .catch(error => {
                            console.error('Error playing track:', error);
                            this.innerHTML = '<i class="fa fa-exclamation-triangle"></i>';
                            setTimeout(() => {
                                this.innerHTML = '<i class="fa fa-play"></i>';
                            }, 2000);
                        });

                    // Update UI when track ends
                    audioPlayer.onended = function() {
                        playButton.innerHTML = '<i class="fa fa-play"></i>';
                        currentlyPlaying = null;
                    };
                }
            });
        }
    });

    // Handle Add to Playlist functionality
    const playlistAddLinks = document.querySelectorAll('.playlist-add');
    playlistAddLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const playlistId = this.getAttribute('data-playlist-id');
            const trackId = this.closest('.dropdown-menu').getAttribute('data-track-id');

            // Make AJAX call to add track to playlist
            fetch('/playlist/addTrack', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `playlist_id=${playlistId}&track_id=${trackId}`
            })
                .then(response => response.json())
                .then(data => {
                    // Show feedback
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fa fa-check me-2"></i>Added';

                    // Restore original text after feedback
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 2000);
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Handle Like buttons for tracks
    const likeButtons = document.querySelectorAll('.like-btn');
    likeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const trackId = this.closest('.track-item').getAttribute('data-track-id');

            // Toggle liked status
            if (this.classList.contains('liked')) {
                this.classList.remove('liked');

                // Make AJAX call to unlike
                fetch('/track/unlike', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `track_id=${trackId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(`Unliked track ${trackId}`);
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                this.classList.add('liked');

                // Make AJAX call to like
                fetch('/track/like', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `track_id=${trackId}`
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(`Liked track ${trackId}`);
                    })
                    .catch(error => console.error('Error:', error));

                // Visual feedback animation
                this.querySelector('i').classList.add('pulse-animation');
                setTimeout(() => {
                    this.querySelector('i').classList.remove('pulse-animation');
                }, 700);
            }
        });
    });

    // Album play buttons functionality
    const albumPlayButtons = document.querySelectorAll('.play-button');
    albumPlayButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent card click navigation
            const albumId = this.closest('.card').getAttribute('data-album-id');

            // Make AJAX call to get first track of album
            fetch(`/album/${albumId}/firstTrack`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.track && data.track.audio) {
                        // Play the first track
                        const audioPlayer = document.getElementById('audioPlayer');

                        // Reset all play buttons
                        document.querySelectorAll('.play-track-btn').forEach(btn => {
                            btn.innerHTML = '<i class="fa fa-play"></i>';
                        });

                        audioPlayer.src = data.track.audio;
                        audioPlayer.play()
                            .then(() => {
                                // Update UI to show playing status
                                this.innerHTML = '<i class="fa fa-pause"></i>';
                            })
                            .catch(error => {
                                console.error('Error playing track:', error);
                            });
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // CSS for toast notifications
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