<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php
// instructor.php — Bootstrap layout (no custom <style>), schema-safe

include 'includes/header.inc';
include 'includes/nav.inc';
include __DIR__ . '/includes/db_connect.inc';

/* ---------- Schema selection: local vs Titan ---------- */
$SCHEMA = 'skillswap'; // local/XAMPP DB name
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $SCHEMA = 's4158210'; // Titan schema
}

/* Force DB to avoid “wrong database” issues */
if (method_exists($conn, 'select_db')) {
    @($conn->select_db($SCHEMA));
}

/* ---------- Image base (A3) ---------- */
$IMG_DIR = '/wp/a3/assets/images/skills/';
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $IMG_DIR = '/~s4158210/wp/a3/assets/images/skills/';
}

/* ---------- Helpers ---------- */
function h($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}
function money_hr($v)
{
    return ($v === '' || $v === null) ? '' : ('$' . number_format((float)$v, 2) . '/hr');
}
function starts_with($h, $n)
{
    return substr($h, 0, strlen($n)) === $n;
}

/* ---------- Resolve instructor (?id= or ?user=) ---------- */
$instructor = null;

if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $uid = (int)$_GET['id'];
    $sql = "SELECT user_id, username, COALESCE(bio,'') AS bio
            FROM `{$SCHEMA}`.`users` WHERE user_id = ? LIMIT 1";
    $st = $conn->prepare($sql);
    if (!$st) {
        http_response_code(500);
        die('DB prepare error: ' . h($conn->error));
    }
    $st->bind_param('i', $uid);
    $st->execute();
    $instructor = $st->get_result()->fetch_assoc();
    $st->close();
} elseif (!empty($_GET['user'])) {
    $username = trim($_GET['user']);
    $sql = "SELECT user_id, username, COALESCE(bio,'') AS bio
            FROM `{$SCHEMA}`.`users` WHERE username = ? LIMIT 1";
    $st = $conn->prepare($sql);
    if (!$st) {
        http_response_code(500);
        die('DB prepare error: ' . h($conn->error));
    }
    $st->bind_param('s', $username);
    $st->execute();
    $instructor = $st->get_result()->fetch_assoc();
    $st->close();
}
?>
<main class="container my-5" style="max-width:1100px;">
    <?php if (!$instructor): ?>
        <h1 class="h3 mb-2">Instructor not found</h1>
        <p class="text-muted">Open with <code>?id=USER_ID</code> or <code>?user=username</code>.</p>
</main>
<?php include 'includes/footer.inc'; ?>
</body>

</html>
<?php exit;
    endif; ?>

<?php
/* ---------- Fetch this instructor’s skills ---------- */
$skills = [];
$sql = "SELECT skill_id, title, description, category, image_path, rate_per_hr, level, created_at
        FROM `{$SCHEMA}`.`skills`
        WHERE user_id = ?
        ORDER BY skill_id DESC";
$st = $conn->prepare($sql);
if (!$st) {
    http_response_code(500);
    die('DB prepare error: ' . h($conn->error));
}
$st->bind_param('i', $instructor['user_id']);
$st->execute();
$rs = $st->get_result();
while ($row = $rs->fetch_assoc()) {
    $skills[] = $row;
}
$st->close();
?>

<!-- Header -->
<section class="mb-3">
    <h1 class="display-6 mb-1">Instructor: <?= h($instructor['username']) ?></h1>
    <p class="text-muted small mb-0"><?= h($instructor['bio'] ?: 'Instructor profile.') ?></p>
</section>

<h2 class="h4 mt-4 mb-3">Skills Offered</h2>

<?php if (empty($skills)): ?>
    <p class="text-muted">No skills yet for this instructor.</p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php foreach ($skills as $s):
            $img = trim((string)$s['image_path']);
            if ($img === '') {
                $imgSrc = $IMG_DIR . 'placeholder.png';
            } else {
                $imgSrc = (starts_with($img, 'http://') || starts_with($img, 'https://') || starts_with($img, '/'))
                    ? $img
                    : $IMG_DIR . rawurlencode($img);
            }
        ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="<?= h($imgSrc) ?>" class="card-img-top" alt="<?= h($s['title']) ?>">
                    <div class="card-body">
                        <h5 class="card-title fs-6 mb-2">
                            <a class="link-dark text-decoration-none" href="details.php?id=<?= (int)$s['skill_id'] ?>">
                                <?= h($s['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text small text-muted mb-3">Rate: <?= h(money_hr($s['rate_per_hr'])) ?></p>
                        <a href="details.php?id=<?= (int)$s['skill_id'] ?>" class="btn btn-outline-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</main>
<?php include 'includes/footer.inc'; ?>
</body>

</html>