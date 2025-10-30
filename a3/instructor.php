<?php
// instructor.php — SkillSwap (Bootstrap layout matching reference)

include 'includes/header.inc';
include 'includes/nav.inc';
include __DIR__ . '/includes/db_connect.inc';

// Force DB (keeps things consistent)
if (method_exists($conn, 'select_db')) {
    $conn->select_db('skillswap');
}

// Image base (A3)
$IMG_DIR = '/wp/a3/assets/images/skills/';
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $IMG_DIR = '/~s4158210/wp/a3/assets/images/skills/';
}

// Helpers
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

// Resolve instructor (?id= or ?user=)
$instructor = null;
if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
    $uid = (int)$_GET['id'];
    $st = $conn->prepare('SELECT user_id, username, COALESCE(bio,"") AS bio FROM `skillswap`.`users` WHERE user_id = ? LIMIT 1');
    $st->bind_param('i', $uid);
    $st->execute();
    $instructor = $st->get_result()->fetch_assoc();
    $st->close();
} elseif (!empty($_GET['user'])) {
    $username = trim($_GET['user']);
    $st = $conn->prepare('SELECT user_id, username, COALESCE(bio,"") AS bio FROM `skillswap`.`users` WHERE username = ? LIMIT 1');
    $st->bind_param('s', $username);
    $st->execute();
    $instructor = $st->get_result()->fetch_assoc();
    $st->close();
}

?>
<main class="container" style="max-width:1100px;margin:32px auto 64px;padding:0 16px;">

    <?php if (!$instructor): ?>
        <h1 class="mb-2">Instructor not found</h1>
        <p class="text-muted">Open with <code>?id=USER_ID</code> or <code>?user=username</code>.</p>
</main>
<?php include 'includes/footer.inc'; ?></body>

</html>
<?php exit;
    endif; ?>

<?php
// Fetch this instructor’s skills
$skills = [];
$st = $conn->prepare('
    SELECT skill_id, title, description, category, image_path, rate_per_hr, level, created_at
    FROM `skillswap`.`skills`
    WHERE user_id = ?
    ORDER BY skill_id DESC
');
$st->bind_param('i', $instructor['user_id']);
$st->execute();
$rs = $st->get_result();
while ($row = $rs->fetch_assoc()) {
    $skills[] = $row;
}
$st->close();
?>

<!-- Page styles to match the reference -->
<style>
    /* Headings color & type feel to match the screenshot */
    .ss-accent {
        color: #b84b1f;
    }

    /* warm orange-brown used in your UI */
    .ss-h1 {
        font-size: 40px;
        line-height: 1.2;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .ss-sub {
        color: #6b6b6b;
        font-size: 13px;
    }

    .ss-h2 {
        font-size: 28px;
        font-weight: 700;
        margin: 22px 0 18px;
    }

    /* Card look: big image, no border, soft shadow, rounded corners */
    .ss-card {
        border: 0;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .08);
        background: #fff;
    }

    .ss-imgwrap {
        aspect-ratio: 16 / 11;
        overflow: hidden;
    }

    .ss-imgwrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Title and meta under image */
    .ss-title {
        font-size: 14px;
        font-weight: 700;
        margin: 10px 0 6px;
        color: #b84b1f;
        text-decoration: none;
    }

    .ss-meta {
        font-size: 12px;
        color: #7a7a7a;
        margin-bottom: 10px;
    }

    /* Small pill “View” button in warm outline */
    .btn-view {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid #b84b1f;
        color: #b84b1f;
        background: transparent;
    }

    .btn-view:hover {
        color: #fff;
        background: #b84b1f;
    }

    /* Grid spacing like screenshot (two-up comfortably) */
    @media (min-width: 768px) {
        .ss-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 28px;
        }
    }

    @media (max-width: 767.98px) {
        .ss-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 22px;
        }
    }
</style>

<!-- Header -->
<section class="mb-2">
    <h1 class="ss-h1 ss-accent">Instructor: <?php echo h($instructor['username']); ?></h1>
    <p class="ss-sub"><?php echo h($instructor['bio'] ?: 'Guitar teacher and music enthusiast.'); ?></p>
</section>

<h2 class="ss-h2 ss-accent">Skills Offered</h2>

<?php if (empty($skills)): ?>
    <p class="text-muted">No skills yet for this instructor.</p>
<?php else: ?>
    <div class="ss-grid">
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
            <article class="ss-card">
                <div class="ss-imgwrap">
                    <img src="<?php echo h($imgSrc); ?>" alt="<?php echo h($s['title']); ?>">
                </div>
                <div class="p-3">
                    <a class="ss-title" href="details.php?id=<?php echo (int)$s['skill_id']; ?>">
                        <?php echo h($s['title']); ?>
                    </a>
                    <div class="ss-meta">Rate: <?php echo h(money_hr($s['rate_per_hr'])); ?></div>
                    <a class="btn btn-view" href="details.php?id=<?php echo (int)$s['skill_id']; ?>">View</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</main>
<?php include 'includes/footer.inc'; ?>
</body>

</html>