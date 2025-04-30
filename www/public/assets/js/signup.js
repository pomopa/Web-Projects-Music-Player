function handleImagePreview(input) {
    const fileLabel = document.getElementById('fileLabel');
    const preview = document.getElementById('previewImage');
    const container = document.getElementById('previewContainer');
    const inputGroup = document.getElementById('fileInputGroup');
    const profilePicture = document.getElementById('fileNameLabel');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
            fileLabel.textContent = input.files[0].name;

            // Afegeix classe que fa pujar el label
            inputGroup.classList.add('is-focused');
            profilePicture.style.position = "relative";
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        fileLabel.textContent = 'Profile Picture';
        container.style.display = 'none';

        // Elimina la classe si es deselecciona
        inputGroup.classList.remove('is-filled');
        profilePicture.style.position = "";
    }
}