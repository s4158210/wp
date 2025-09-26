<?php /* /wp/a2/details.php */ ?>
<?php
include __DIR__ . '/includes/db_connect.inc';

    $IMG_DIR  = '/wp/a2/assets/images/skills/';
    // <-- your folder per screenshot

    if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    http_response_code(400);
    exit('Invalid request.');
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

// Build the final URL from filename stored in DB (e.g., "1.png")
$imgUrl = $IMG_DIR . ltrim($skill['image_path'], '/');
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.inc'; ?>

<body>
    <?php include 'includes/nav.inc'; ?>

    <div class="container my-5">
        <h1><?= htmlspecialchars($skill['title']) ?></h1>

        <div class="row mt-4">
            <div class="col-md-5">
                <img src="<?= htmlspecialchars($imgUrl) ?>"
                    alt="<?= htmlspecialchars($skill['title']) ?>"
                    class="img-fluid rounded" style="max-width:240px">
            </div>

            <div class="col-md-7">
                <p><?= nl2br(htmlspecialchars($skill['description'])) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($skill['category']) ?></p>
                <p><strong>Level:</strong> <?= htmlspecialchars($skill['level']) ?></p>
                <p><strong>Rate:</strong> $<?= htmlspecialchars($skill['rate_per_hr']) ?>/hr</p>
                <a href="/wp/a2/index.php" class="btn btn-secondary mt-3">← Back to Home</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.inc'; ?>
</body>

</html>