document.addEventListener("DOMContentLoaded", function () {
    // ✅ 1x1 transparent GIF so <img> never has an empty src
    const PLACEHOLDER =
        "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";

    // ✅ Modal image pop-up
    const modalImage = document.getElementById("modalImage");
    const galleryImages = document.querySelectorAll(".gallery");

    // Initialize modal image to a safe default (prevents validator error)
    if (modalImage && !modalImage.src) {
        modalImage.src = PLACEHOLDER;
        modalImage.alt = "popup image";
    }

    // When a gallery image is clicked, load its full-size URL into the modal
    if (modalImage && galleryImages.length) {
        galleryImages.forEach((img) => {
            img.addEventListener("click", () => {
                const src = img.getAttribute("data-bs-image");
                // Debug (optional): console.log("Clicked image source:", src);
                modalImage.src = src || PLACEHOLDER;
                modalImage.alt = img.alt || "Skill image";
            });
        });

        // Optional: when the modal closes, reset to placeholder to avoid stale image flashes
        const imageModal = document.getElementById("imageModal");
        if (imageModal) {
            imageModal.addEventListener("hidden.bs.modal", () => {
                modalImage.src = PLACEHOLDER;
                modalImage.alt = "popup image";
            });
        }
    }

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
