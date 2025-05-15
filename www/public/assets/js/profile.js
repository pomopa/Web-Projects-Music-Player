document.getElementById('profilePicture').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const profileContainer = document.querySelector('.rounded-circle');

            if (profileContainer.tagName === 'IMG') {
                profileContainer.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList = 'rounded-circle img-fluid border border-success';
                img.style = 'width: 150px; height: 150px; object-fit: cover;';
                img.alt = 'Profile Picture';

                profileContainer.parentNode.replaceChild(img, profileContainer);
            }
        }
        reader.readAsDataURL(file);
    }
});