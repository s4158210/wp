<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<body>

    <!--Navigation bar-->
    <?php
    include 'includes/nav.inc';
    include __DIR__ . '/includes/db_connect.inc';

    // ✅ Correct base path for images
    $IMG_DIR = '/wp/a2/assets/images/skills/';

    // Fetch skills
    $sql = "SELECT skill_id, title, image_path FROM skills ORDER BY skill_id ASC";
    $result = $conn->query($sql);
    ?>

    <!--Gallery content-->
    <main class="container my-5">
        <h1 class="mb-4">Skill Gallery</h1>
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()):
                    $title = htmlspecialchars($row['title']);
                    $img   = $IMG_DIR . ltrim($row['image_path'], '/');
                ?>
                    <div class="col-6 col-md-3">
                        <img src="<?php echo htmlspecialchars($img); ?>"
                            class="img-fluid rounded gallery"
                            data-bs-toggle="modal"
                            data-bs-target="#imageModal"
                            data-bs-image="<?php echo htmlspecialchars($img); ?>"
                            alt="<?php echo $title; ?>">
                        <p class="text-center mt-2"><?php echo $title; ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No skills found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!--Modal for image pop-up-->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-label="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <img src="" id="modalImage" class="img-fluid rounded-top" alt="popup img">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!--Footer-->
    <?php include 'includes/footer.inc'; ?>

    <!--Scripts-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>