<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<body>

    <?php
    include 'includes/nav.inc';
    include __DIR__ . '/includes/db_connect.inc';


    // 🔎 Debugging enabled (remove on production)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // define upload dir
    $IMG_DIR = __DIR__ . "/assets/images/skills/";
    $message = "";

    // ---- Handle form submit ----
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category = trim($_POST['category']);
        $rate = (float)$_POST['rate'];
        $level = trim($_POST['level']);

        if ($title && $description && $category && $rate > 0 && $level) {
            // ✅ Handle image upload
            if (isset($_FILES['skillImage']) && $_FILES['skillImage']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['skillImage']['name'], PATHINFO_EXTENSION));
                $newFile = uniqid("skill_", true) . "." . $ext;
                $uploadPath = $IMG_DIR . $newFile;

                if (move_uploaded_file($_FILES['skillImage']['tmp_name'], $uploadPath)) {
                    // Save filename to DB
                    $sql = "INSERT INTO skills (title, description, category, rate_per_hr, level, image_path, created_at) 
                            VALUES (?, ?, ?, ?, ?, ?, NOW())";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssdss", $title, $description, $category, $rate, $level, $newFile);

                    if ($stmt->execute()) {
                        $message = "<div class='alert alert-success mt-3'>✅ Skill added successfully!</div>";
                    } else {
                        $message = "<div class='alert alert-danger mt-3'>❌ Database error: " . $stmt->error . "</div>";
                    }
                    $stmt->close();
                } else {
                    $message = "<div class='alert alert-danger mt-3'>❌ Failed to upload image.</div>";
                }
            } else {
                $message = "<div class='alert alert-danger mt-3'>❌ Please upload an image.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning mt-3'>⚠️ Please fill all required fields correctly.</div>";
        }
    }
    ?>

    <main class="container py-5">
        <h1 class="mb-4">Add New Skill</h1>

        <!-- Display feedback -->
        <?php if ($message) echo $message; ?>

        <form id="skillForm" method="POST" action="" enctype="multipart/form-data" novalidate>

            <div class="mb-3">
                <label for="title" class="form-label">Title *</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="title" required />
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="description" required></textarea>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category *</label>
                <input type="text" class="form-control" id="category" name="category" placeholder="category" required />
            </div>

            <div class="mb-3">
                <label for="rate" class="form-label">Rate per Hour ($) *</label>
                <input type="number" step="0.01" class="form-control" id="rate" name="rate" placeholder="99.65" required />
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
                    accept=".jpg,.jpeg,.png,.gif,.webp" required />
                <div class="form-text">Only image files are allowed (JPG, JPEG, PNG, GIF, WEBP).</div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

    </main>

    <?php include 'includes/footer.inc'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>