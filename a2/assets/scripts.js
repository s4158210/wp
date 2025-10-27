document.addEventListener("DOMContentLoaded", function () {
    // ✅ Modal image pop-up
    const modalImage = document.getElementById("modalImage");
    const galleryImages = document.querySelectorAll(".gallery");

    galleryImages.forEach(img => {
        img.addEventListener("click", () => {
            const src = img.getAttribute("data-bs-image");
            console.log("Clicked image source:", src); // Debugging
            modalImage.src = src;
            modalImage.alt = img.alt || "Skill image";
        });
    });

    // ✅ File input validation
    const form = document.getElementById("skillForm");
    const imageInput = document.getElementById("skillImage");

    if (form && imageInput) {
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
    }
});
