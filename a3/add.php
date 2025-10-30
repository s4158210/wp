<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.inc'; ?>

<body>
    <?php
    include 'includes/nav.inc';
    include __DIR__ . '/includes/db_connect.inc';

    /* Use A3 paths to match the rest of your app */
    $IMG_DIR_FS = __DIR__ . "/assets/images/skills/"; // filesystem
    $IMG_DIR_URL = "/wp/a3/assets/images/skills/";
    if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
        $IMG_DIR_URL = "/~s4158210/wp/a3/assets/images/skills/";
    }

    /* Helpers */
    function skills_has_user_id(mysqli $conn): bool
    {
        $res = $conn->query("SHOW COLUMNS FROM `skills` LIKE 'user_id'");
        return $res && $res->num_rows > 0;
    }

    /* Ensure we have a valid user_id in session (fallback via username) */
    $loggedInUserId = $_SESSION['user_id'] ?? null;
    if ($loggedInUserId === null && !empty($_SESSION['username'])) {
        if ($st = $conn->prepare("SELECT user_id FROM users WHERE username = ? LIMIT 1")) {
            $st->bind_param("s", $_SESSION['username']);
            $st->execute();
            $r = $st->get_result();
            if ($row = $r->fetch_assoc()) {
                $loggedInUserId = (int)$row['user_id'];
                $_SESSION['user_id'] = $loggedInUserId; // cache for future inserts
            }
            $st->close();
        }
    }

    /* If not logged in, block submission (prevents user_id = 0 inserts) */
    $requireLogin = true; // set false if you want anonymous skills (not recommended)
    if ($requireLogin && ($_SERVER['REQUEST_METHOD'] === 'POST') && !$loggedInUserId) {
        echo "<main class='container py-5'><div class='alert alert-danger'>Please log in before adding a skill.</div></main>";
        include 'includes/footer.inc';
        echo "</body></html>";
        exit;
    }

    $message = "";
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title       = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category    = trim($_POST['category']);
        $rate        = isset($_POST['rate']) ? (float)$_POST['rate'] : 0.0;
        $level       = trim($_POST['level']);

        if ($title && $description && $category && $rate > 0 && $level) {
            if (isset($_FILES['skillImage']) && $_FILES['skillImage']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['skillImage']['name'], PATHINFO_EXTENSION));
                $newFile = uniqid("skill_", true) . "." . $ext;
                $uploadPath = $IMG_DIR_FS . $newFile;

                if (move_uploaded_file($_FILES['skillImage']['tmp_name'], $uploadPath)) {

                    $useUserId = skills_has_user_id($conn);

                    if ($useUserId) {
                        // REQUIRE a valid owner
                        if (!$loggedInUserId) {
                            $message = "<div class='alert alert-danger mt-3'>❌ Could not determine your account. Please log in again.</div>";
                        } else {
                            $sql = "INSERT INTO skills (title, description, category, rate_per_hr, level, image_path, user_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
                            if ($stmt = $conn->prepare($sql)) {
                                $uid = (int)$loggedInUserId; // ensure integer
                                $stmt->bind_param("sssdssi", $title, $description, $category, $rate, $level, $newFile, $uid);
                                if ($stmt->execute()) {
                                    // Optional: redirect straight to the instructor page
                                    header("Location: instructor.php?id=" . $uid);
                                    exit;
                                } else {
                                    $message = "<div class='alert alert-danger mt-3'>❌ Database error: " . htmlspecialchars($stmt->error) . "</div>";
                                }
                                $stmt->close();
                            } else {
                                $message = "<div class='alert alert-danger mt-3'>❌ Database prepare error.</div>";
                            }
                        }
                    } else {
                        // Legacy path (no user_id column)
                        $sql = "INSERT INTO skills (title, description, category, rate_per_hr, level, image_path) 
                            VALUES (?, ?, ?, ?, ?, ?)";
                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("sssdss", $title, $description, $category, $rate, $level, $newFile);
                            if ($stmt->execute()) {
                                $message = "<div class='alert alert-success mt-3'>✅ Skill added successfully!</div>";
                            } else {
                                $message = "<div class='alert alert-danger mt-3'>❌ Database error: " . htmlspecialchars($stmt->error) . "</div>";
                            }
                            $stmt->close();
                        } else {
                            $message = "<div class='alert alert-danger mt-3'>❌ Database prepare error.</div>";
                        }
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
        <?= $message ?>
        <form id="skillForm" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" novalidate>
            <div class="mb-3">
                <label for="title" class="form-label">Title *</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category *</label>
                <input type="text" class="form-control" id="category" name="category" required>
            </div>
            <div class="mb-3">
                <label for="rate" class="form-label">Rate per Hour ($) *</label>
                <input type="number" step="0.01" class="form-control" id="rate" name="rate" required>
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
                <input type="file" class="form-control" id="skillImage" name="skillImage" accept=".jpg,.jpeg,.png,.gif,.webp" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </main>

    <?php include 'includes/footer.inc'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/wp/a3/assets/scripts.js"></script>
</body>

</html>