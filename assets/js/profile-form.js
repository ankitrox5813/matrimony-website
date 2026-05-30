/**
 * Profile / preference forms: file upload preview & filename display
 */
function initProfilePhotoUpload() {
  const input = document.getElementById('profilePhotoInput');
  if (!input) return;

  const previewImg = document.getElementById('profilePhotoPreview');
  const fileNameEl = document.getElementById('profilePhotoFileName');

  input.addEventListener('change', function () {
    const file = this.files && this.files[0];

    if (!file) {
      if (fileNameEl) fileNameEl.textContent = 'No file chosen';
      return;
    }

    if (fileNameEl) {
      fileNameEl.textContent = file.name;
    }

    if (!previewImg || !file.type.startsWith('image/')) {
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      previewImg.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
}

document.addEventListener('DOMContentLoaded', initProfilePhotoUpload);
