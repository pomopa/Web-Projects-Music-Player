let currentAudio = null;
let currentPlaylistTracks = [];
let currentTrackIndex = 0;

function playPlaylist(tracks, playlistName) {
    if (!tracks || tracks.length === 0) return;

    currentPlaylistTracks = tracks;
    currentTrackIndex = 0;
    playCurrentTrack(playlistName);
}

function playCurrentTrack(playlistName) {
    const track = currentPlaylistTracks[currentTrackIndex];
    if (!track) return;

    if (currentAudio) {
        currentAudio.pause();
        currentAudio = null;
    }

    currentAudio = new Audio(track.player_url);
    currentAudio.play();

    document.getElementById('playlistPlayerBar').style.display = 'block';
    document.getElementById('playerPlaylistName').textContent = playlistName;
    document.getElementById('playerTrackName').textContent = track.name;
    document.getElementById('pauseResumeIcon').classList.remove('fa-play');
    document.getElementById('pauseResumeIcon').classList.add('fa-pause');

    currentAudio.addEventListener('ended', () => {
        playNextTrack();
    });
}

function pauseResumeTrack() {
    if (!currentAudio) return;

    if (currentAudio.paused) {
        currentAudio.play();
        document.getElementById('pauseResumeIcon').classList.remove('fa-play');
        document.getElementById('pauseResumeIcon').classList.add('fa-pause');
    } else {
        currentAudio.pause();
        document.getElementById('pauseResumeIcon').classList.remove('fa-pause');
        document.getElementById('pauseResumeIcon').classList.add('fa-play');
    }
}

function playNextTrack() {
    if (currentTrackIndex + 1 < currentPlaylistTracks.length) {
        currentTrackIndex++;
        playCurrentTrack(document.getElementById('playerPlaylistName').textContent);
    } else {
        currentAudio.pause();
        document.getElementById('playlistPlayerBar').style.display = 'none';
        currentAudio = null;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('pauseResumeBtn')?.addEventListener('click', pauseResumeTrack);
    document.getElementById('nextTrackBtn')?.addEventListener('click', playNextTrack);
});
