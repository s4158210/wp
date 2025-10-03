<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<body>
    <?php
    include 'includes/nav.inc';
    include __DIR__ . '/includes/db_connect.inc';

    $IMG_DIR = '/wp/a2/assets/images/skills/';
    if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
        $IMG_DIR = '/~s4158210/wp/a2/assets/images/skills/';
    }

    $message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title       = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category    = trim($_POST['category']);
        $rate        = (float)$_POST['rate'];
        $level       = trim($_POST['level']);

        if ($title && $description && $category && $rate > 0 && $level) {
            if (isset($_FILES['skillImage']) && $_FILES['skillImage']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['skillImage']['name'], PATHINFO_EXTENSION));
                $newFile = uniqid("skill_", true) . "." . $ext;
                $uploadPath = __DIR__ . "/assets/images/skills/" . $newFile;

                if (move_uploaded_file($_FILES['skillImage']['tmp_name'], $uploadPath)) {
                    $sql = "INSERT INTO skills (title, description, category, rate_per_hr, level, image_path) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("sssdss", $title, $description, $category, $rate, $level, $newFile);
                        if ($stmt->execute()) {
                            $message = "<div class='alert alert-success mt-3'>✅ Skill added successfully!</div>";
                        } else {
                            $message = "<div class='alert alert-danger mt-3'>❌ Database error: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }
                } else {
                    $message = "<div class='alert alert-danger mt-3'>❌ Failed to upload image.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger mt-3'>⚠️ Please upload an image.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning mt-3'>⚠️ Please fill all required fields correctly.</div>";
        }
    }
    ?>

    <main class="container py-5">
        <h1 class="mb-4">Add New Skill</h1>

        <!-- Feedback message -->
        <?= $message ?>

        <!-- Form -->
        <form id="skillForm" method="POST"
            action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>"
            enctype="multipart/form-data" novalidate>

            <div class="mb-3">
                <label for="title" class="form-label">Title *</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Title" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description" required></textarea>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category *</label>
                <input type="text" class="form-control" id="category" name="category" placeholder="Category" required>
            </div>

            <div class="mb-3">
                <label for="rate" class="form-label">Rate per Hour ($) *</label>
                <input type="number" step="0.01" class="form-control" id="rate" name="rate" placeholder="99.65" required>
            </div>

            <div class="mb-3">
                <label for="level" class="form-label">Level *</label>
                <select class="form-select" id="level" name="level" required>
                    <option value="">Please select</option>
                    <option>Beginner</option>
                    <option>Intermediate</option>
                    <option>Expert</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="skillImage" class="form-label">Skill Image *</label>
                <input type="file" class="form-control" id="skillImage" name="skillImage"
                    accept=".jpg,.jpeg,.png,.gif,.webp" required>
                <div class="form-text">Only image files allowed (JPG, JPEG, PNG, GIF, WEBP).</div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </main>

    <?php include 'includes/footer.inc'; ?>

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/wp/a2/assets/scripts.js"></script>
</body>

</html>