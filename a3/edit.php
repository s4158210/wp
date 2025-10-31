<?php
// edit.php — owner-only edit; same form as add.php; optional image replacement

// Show errors while debugging (optional)
ini_set('display_errors', '1');
error_reporting(E_ALL);

session_start();

// DB first (no visible output yet)
include __DIR__ . '/includes/db_connect.inc';

// Force DB (consistent)
if (method_exists($conn, 'select_db')) $conn->select_db('skillswap');

// Auth guard BEFORE any HTML output
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// CSRF
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

function h($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

$IMG_DIR_FS  = __DIR__ . "/assets/images/skills/";  // filesystem
$IMG_DIR_URL = "/wp/a3/assets/images/skills/";      // url
if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $IMG_DIR_URL = "/~s4158210/wp/a3/assets/images/skills/";
}

// Validate id BEFORE output
$id = (isset($_GET['id']) && ctype_digit($_GET['id'])) ? (int)$_GET['id'] : 0;
if (!$id) {
    http_response_code(400);
    // now it's safe to include header for nice message
    include 'includes/header.inc';
    include 'includes/nav.inc';
    echo "<main class='container py-5'>Invalid id.</main>";
    include 'includes/footer.inc';
    exit;
}

// Load skill
$st = $conn->prepare("SELECT skill_id, user_id, title, description, category, rate_per_hr, level, image_path FROM skills WHERE skill_id = ? LIMIT 1");
$st->bind_param("i", $id);
$st->execute();
$res = $st->get_result();
$skill = $res ? $res->fetch_assoc() : null;
$st->close();

if (!$skill) {
    http_response_code(404);
    include 'includes/header.inc';
    include 'includes/nav.inc';
    echo "<main class='container py-5'>Skill not found.</main>";
    include 'includes/footer.inc';
    exit;
}
if ((int)$skill['user_id'] !== (int)$_SESSION['user_id']) {
    http_response_code(403);
    include 'includes/header.inc';
    include 'includes/nav.inc';
    echo "<main class='container py-5'>You do not have permission to edit this skill.</main>";
    include 'includes/footer.inc';
    exit;
}

// At this point it's safe to render the page chrome
include 'includes/header.inc';
include 'includes/nav.inc';

$message = "";
$title       = $skill['title'];
$description = $skill['description'];
$category    = $skill['category'];
$rate        = (string)$skill['rate_per_hr'];
$level       = $skill['level'];
$oldImage    = $skill['image_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        die("Bad CSRF token");
    }

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category    = trim($_POST['category']);
    $rate        = isset($_POST['rate']) ? (float)$_POST['rate'] : 0.0;
    $level       = trim($_POST['level']);
    $newImage    = $oldImage; // keep existing unless replaced

    if ($title && $description && $category && $rate > 0 && $level) {
        // Optional image replacement
        if (isset($_FILES['skillImage']) && $_FILES['skillImage']['error'] === UPLOAD_ERR_OK && $_FILES['skillImage']['size'] > 0) {
            $ext = strtolower(pathinfo($_FILES['skillImage']['name'], PATHINFO_EXTENSION));
            $fname = uniqid("skill_", true) . "." . $ext;
            $dst = $IMG_DIR_FS . $fname;
            if (move_uploaded_file($_FILES['skillImage']['tmp_name'], $dst)) {
                // delete old file if local
                if (!empty($oldImage)) {
                    $oldPath = $IMG_DIR_FS . basename($oldImage);
                    if (is_file($oldPath)) @unlink($oldPath);
                }
                $newImage = $fname;
            } else {
                $message = "<div class='alert alert-danger mt-3'>❌ Failed to upload replacement image.</div>";
            }
        }

        // Update DB
        $sql = "UPDATE skills
                SET title = ?, description = ?, category = ?, rate_per_hr = ?, level = ?, image_path = ?
                WHERE skill_id = ? AND user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $uid = (int)$_SESSION['user_id'];
            $stmt->bind_param("sssdssii", $title, $description, $category, $rate, $level, $newImage, $id, $uid);
            if ($stmt->execute()) {
                header("Location: details.php?id=" . $id);
                exit;
            } else {
                $message = "<div class='alert alert-danger mt-3'>❌ Database error: " . h($stmt->error) . "</div>";
            }
            $stmt->close();
        } else {
            $message = "<div class='alert alert-danger mt-3'>❌ Database prepare error.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning mt-3'>⚠️ Please fill all required fields correctly.</div>";
    }
}
?>

<main class="container py-5" style="max-width:1100px;">
    <h1 class="mb-4" style="color:#b84b1f;">Edit Skill</h1>
    <?= $message ?>

    <!-- Same form structure/styles as add.php -->
    <form id="skillForm" method="POST" action="<?= h($_SERVER['PHP_SELF']) . '?id=' . (int)$id ?>" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">

        <div class="mb-3">
            <label for="title" class="form-label">Title *</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= h($title) ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description *</label>
            <textarea class="form-control" id="description" name="description" rows="4" required><?= h($description) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category *</label>
            <input type="text" class="form-control" id="category" name="category" value="<?= h($category) ?>" required>
        </div>

        <div class="mb-3">
            <label for="rate" class="form-label">Rate per Hour ($) *</label>
            <input type="number" step="0.01" class="form-control" id="rate" name="rate" value="<?= h($rate) ?>" required>
        </div>

        <div class="mb-3">
            <label for="level" class="form-label">Level *</label>
            <select class="form-select" id="level" name="level" required>
                <?php
                $levels = ['Beginner', 'Intermediate', 'Expert'];
                foreach ($levels as $lv) {
                    $sel = ($lv === $level) ? 'selected' : '';
                    echo "<option $sel>" . h($lv) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-2">
            <label class="form-label">Current Image</label><br>
            <?php
            $img = $oldImage ? $IMG_DIR_URL . basename($oldImage) : $IMG_DIR_URL . 'placeholder.png';
            ?>
            <img src="<?= h($img) ?>" alt="Current image" style="height:80px;border-radius:6px;">
        </div>

        <div class="mb-3">
            <label for="skillImage" class="form-label">Replace Image (optional)</label>
            <input type="file" class="form-control" id="skillImage" name="skillImage" accept=".jpg,.jpeg,.png,.gif,.webp">
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</main>

<?php include 'includes/footer.inc'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>