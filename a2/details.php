<?php /* /wp/a2/details.php */ ?>
<?php
include __DIR__ . '/includes/db_connect.inc';

$IMG_DIR  = '/wp/a2/assets/images/skills/';

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

$imgUrl = $IMG_DIR . ltrim($skill['image_path'], '/');
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

        <div class=>
            <!-- Image -->
            <div class="col-md-4 text-start">
                <img src="<?= htmlspecialchars($imgUrl) ?>"
                    alt="<?= htmlspecialchars($skill['title']) ?>"
                    class="img-fluid rounded mb-4"
                    style="max-width: 250px;">
            </div>

            <!-- Details -->
            <div class="col-md-8 text-start">
                <p><?= nl2br(htmlspecialchars($skill['description'])) ?></p>
                <p><span style="font-weight:bold; color:#b23c17;">Category:</span> <?= htmlspecialchars($skill['category']) ?></p>
                <p><span style="font-weight:bold; color:#b23c17;">Level:</span> <?= htmlspecialchars($skill['level']) ?></p>
                <p><span style="font-weight:bold; color:#b23c17;">Rate:</span> $<?= htmlspecialchars($skill['rate_per_hr']) ?>/hr</p>

                <!-- Back button -->
                <a href="/wp/a2/index.php" class="btn btn-secondary mt-3">← Back to Home</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.inc'; ?>
</body>

</html>