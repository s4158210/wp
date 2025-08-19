const modalImage = document.getElementById('modalImage');
const galleryImages = document.querySelectorAll('.gallery-img');

galleryImages.forEach(img => {
    img.addEventListener('click', () => {
        const src = img.getAttribute('data-bs-image');
        modalImage.src = src;
    });
});