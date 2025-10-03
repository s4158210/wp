<?php
include __DIR__ . '/includes/db_connect.inc';

$IMG_DIR  = '/wp/a2/assets/images/skills/';

if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $IMG_DIR = '/~s4158210/wp/a2/assets/images/skills/';
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit('Invalid request.');
}

$BASE_URL = '/wp/a2/';
if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $BASE_URL = '/~s4158210/wp/a2/';
}

$JS_DIR = "/wp/a2/assets/";
if (strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $JS_DIR = "/~s4158210/wp/a2/assets/";
}


$skill_id = (int)$_GET['id'];

$stmt = $conn->prepare('SELECT title, description, category, image_path, rate_per_hr, level
                        FROM skills WHERE skill_id = ?');
$stmt->bind_param('i', $skill_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    http_response_code(404);
    exit('Skill not found.');
}
$skill = $res->fetch_assoc();
$stmt->close();
$conn->close();

$imgUrl = $IMG_DIR . basename($skill['image_path']);
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
                <p><span style="font-weight:bold; color:#b23c17;">Category:</span> <?= htmlspecialchars($skill['category']) ?></p>
                <p><span style="font-weight:bold; color:#b23c17;">Level:</span> <?= htmlspecialchars($skill['level']) ?></p>
                <p><span style="font-weight:bold; color:#b23c17;">Rate:</span> $<?= htmlspecialchars($skill['rate_per_hr']) ?>/hr</p>

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

    <?php include 'includes/footer.inc'; ?>
    <script src="<?= $JS_DIR ?>scripts.js"></script>
</body>

</html>