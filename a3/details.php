<?php
// details.php — show one skill + (optionally) instructor info

// NEW: session + CSRF for delete (safe to add; no layout change)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

include __DIR__ . '/includes/db_connect.inc';

// Image base
$IMG_DIR = '/wp/a3/assets/images/skills/';
if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $IMG_DIR = '/~s4158210/wp/a3/assets/images/skills/';
}

// Validate id
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit('Invalid request.');
}

$skill_id = (int)$_GET['id'];

// Base URLs
$BASE_URL = '/wp/a3/';
$JS_DIR   = '/wp/a3/assets/';
if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $BASE_URL = '/~s4158210/wp/a3/';
    $JS_DIR   = '/~s4158210/wp/a3/assets/';
}

// Pull the skill + optional instructor (LEFT JOIN so old rows still work)
$sql = "
    SELECT
        s.title,
        s.description,
        s.category,
        s.image_path,
        s.rate_per_hr,
        s.level,
        s.user_id,
        u.username AS instructor_name,
        u.bio      AS instructor_bio
    FROM skills s
    LEFT JOIN users u ON u.user_id = s.user_id
    WHERE s.skill_id = ?
    LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $skill_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    http_response_code(404);
    exit('Skill not found.');
}
$skill = $res->fetch_assoc();
$stmt->close(); // keep $conn open for footer

$imgUrl = $IMG_DIR . basename($skill['image_path']);

// NEW: determine if the viewer is the owner (no layout change)
$viewerId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
$isOwner  = $viewerId && $viewerId === (int)$skill['user_id'];
?>
<!DOCTYPE html>
<html lang="en">

<?php include 'includes/header.inc'; ?>

<body>
    <?php include 'includes/nav.inc'; ?>

    <div class="container my-5">
        <!-- Title -->
        <h1 class="mb-4 text-start" style="color:#cd4f07; font-weight:700;">
            <?= htmlspecialchars($skill['title']) ?>
        </h1>

        <div>
            <!-- Image -->
            <div class="col-md-4 text-start">
                <img src="<?= htmlspecialchars($imgUrl) ?>"
                    alt="<?= htmlspecialchars($skill['title']) ?>"
                    class="img-fluid rounded mb-4 gallery"
                    style="max-width: 300px; cursor:pointer;"
                    data-bs-toggle="modal"
                    data-bs-target="#imageModal"
                    data-bs-image="<?= htmlspecialchars($imgUrl) ?>">
            </div>

            <!-- Details -->
            <div class="col-md-8 text-start">
                <p><?= nl2br(htmlspecialchars($skill['description'])) ?></p>

                <?php if (!empty($skill['instructor_name'])): ?>
                    <p><span style="font-weight:bold; color:#b23c17;">Instructor:</span>
                        <?= htmlspecialchars($skill['instructor_name']) ?>
                    </p>
                    <?php if (!empty($skill['instructor_bio'])): ?>
                        <p class="mb-2">
                            <em><?= nl2br(htmlspecialchars($skill['instructor_bio'])) ?></em>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>

                <p><span style="font-weight:bold; color:#b23c17;">Category:</span> <?= htmlspecialchars($skill['category']) ?></p>
                <p><span style="font-weight:bold; color:#b23c17;">Level:</span> <?= htmlspecialchars($skill['level']) ?></p>
                <p><span style="font-weight:bold; color:#b23c17;">Rate:</span> $<?= htmlspecialchars($skill['rate_per_hr']) ?>/hr</p>

                <!-- NEW: Owner-only buttons (added just above your Back button; no other layout changes) -->
                <?php if ($isOwner): ?>
                    <div class="mt-3 d-flex gap-2">
                        <a class="btn btn-sm btn-warning" href="edit.php?id=<?= (int)$skill_id ?>">Edit Skill</a>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                            Delete Skill
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Back button -->
                <a href="<?= $BASE_URL ?>index.php" class="btn btn-secondary mt-3">← Back to Home</a>
            </div>
        </div>
    </div>

    <!-- Modal for image pop-up -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==" id="modalImage" class="img-fluid rounded-top border border-5 border-white shadow custom-border" alt="popup img">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- NEW: Delete confirmation modal (kept at end; no layout change elsewhere) -->
    <?php if ($isOwner): ?>
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="post" action="delete.php">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to permanently delete
                        <strong><?= htmlspecialchars($skill['title']) ?></strong>?
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
                        <input type="hidden" name="id" value="<?= (int)$skill_id ?>">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <?php include __DIR__ . '/includes/footer.inc'; ?>

    <script src="<?= $JS_DIR ?>scripts.js"></script>
</body>

</html>