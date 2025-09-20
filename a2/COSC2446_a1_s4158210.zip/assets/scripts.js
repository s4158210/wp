
//js function for image pop up
const modalImage = document.getElementById('modalImage');
const galleryImages = document.querySelectorAll('.gallery');

galleryImages.forEach(img => {
    img.addEventListener('click', () => {
        const src = img.getAttribute('data-bs-image');
        modalImage.src = src;
    });
});

//js function for file content 
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("skillForm");
    const imageInput = document.getElementById("skillImage");

    form.addEventListener("submit", function (e) {
        const file = imageInput.files[0];
        if (file) {
            const allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
            if (!allowedTypes.includes(file.type)) {
                e.preventDefault();
                alert("Only image files are allowed (JPG, PNG, GIF, WEBP).");
            }
        } else {
            e.preventDefault();
            alert("Please upload an image file.");
        }
    });
});