<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<body>
    <!-- Navigation -->
    <?php
    include 'includes/nav.inc';
    include __DIR__ . '/includes/db_connect.inc';

    $IMG_DIR = '/wp/a2/assets/images/skills/'; // Local XAMPP

    if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
        $IMG_DIR = '/~s4158210/wp/a2/assets/images/skills/'; // Titan server
    }

    $sql = "SELECT skill_id, title, description, category, level, rate_per_hr, image_path 
            FROM skills ORDER BY created_at DESC";
    $result = $conn->query($sql);

    $JS_DIR = "/wp/a2/assets/";
    if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
        $JS_DIR = "/~s4158210/wp/a2/assets/";
    }
    ?>

    <!-- Gallery content -->
    <main class="container my-5">
        <h1 class="mb-4">Skill Gallery</h1>
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()):
                    $id    = (int)$row['skill_id'];
                    $title = htmlspecialchars($row['title']);
                    $img   = $IMG_DIR . basename($row['image_path']);

                    // Base URL (switch depending on server)
                    $BASE_URL = '/wp/a2/';
                    if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
                        $BASE_URL = '/~s4158210/wp/a2/';
                    }
                ?>
                    <div class="col-6 col-md-3">
                        <img src="<?= htmlspecialchars($img) ?>"
                            class="img-fluid rounded gallery"
                            data-bs-toggle="modal"
                            data-bs-target="#imageModal"
                            data-bs-image="<?= htmlspecialchars($img) ?>"
                            alt="<?= $title ?>">
                        <p class="text-center mt-2">
                            <a href="<?= $BASE_URL ?>details.php?id=<?= $id ?>" class="text-decoration-none">
                                <?= $title ?>
                            </a>
                        </p>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No skills found.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal for image pop-up -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <img src="" id="modalImage" class="img-fluid rounded-top border border-5 border-white shadow custom-border" alt=" popup img">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.inc'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $JS_DIR ?>scripts.js"></script>
</body>

</html>