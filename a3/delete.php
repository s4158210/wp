<?php
// delete.php — owner-only deletion with CSRF; removes DB row and image file

session_start();
include __DIR__ . '/includes/db_connect.inc';

/* Pick schema: local vs Titan */
$SCHEMA = 'skillswap';
if (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'csit.rmit.edu.au') !== false) {
    $SCHEMA = 's4158210';
}
if (method_exists($conn, 'select_db')) {
    @($conn->select_db($SCHEMA));
}

/* Method check */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

/* Auth + CSRF */
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Not logged in');
}
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(400);
    exit('Bad CSRF');
}

/* Validate id */
$id = (isset($_POST['id']) && ctype_digit($_POST['id'])) ? (int)$_POST['id'] : 0;
if (!$id) {
    http_response_code(400);
    exit('Invalid id');
}

/* Load skill (verify owner + get image) */
$sql = "SELECT user_id, image_path FROM `{$SCHEMA}`.`skills` WHERE skill_id = ? LIMIT 1";
$st  = $conn->prepare($sql);
if (!$st) {
    http_response_code(500);
    exit('DB prepare error: ' . htmlspecialchars($conn->error));
}
$st->bind_param("i", $id);
if (!$st->execute()) {
    http_response_code(500);
    exit('DB execute error: ' . htmlspecialchars($st->error));
}

$skill = null;
if (function_exists('mysqli_stmt_get_result')) {
    $res = $st->get_result();
    $skill = $res ? $res->fetch_assoc() : null;
    if ($res) $res->free();
} else {
    $st->store_result();
    if ($st->num_rows > 0) {
        $st->bind_result($r_user_id, $r_image_path);
        $st->fetch();
        $skill = ['user_id' => $r_user_id, 'image_path' => $r_image_path];
    }
}
$st->close();

if (!$skill) {
    http_response_code(404);
    exit('Not found');
}
if ((int)$skill['user_id'] !== (int)$_SESSION['user_id']) {
    http_response_code(403);
    exit('Forbidden');
}

/* Delete DB row */
$uid = (int)$_SESSION['user_id'];
$del = $conn->prepare("DELETE FROM `{$SCHEMA}`.`skills` WHERE skill_id = ? AND user_id = ? LIMIT 1");
if (!$del) {
    http_response_code(500);
    exit('DB prepare error: ' . htmlspecialchars($conn->error));
}
$del->bind_param("ii", $id, $uid);
$ok = $del->execute();
$del->close();

/* If OK, remove image and redirect */
if ($ok) {
    if (!empty($skill['image_path'])) {
        $IMG_DIR_FS = __DIR__ . "/assets/images/skills/";
        $path = $IMG_DIR_FS . basename($skill['image_path']);
        if (is_file($path)) {
            @unlink($path);
        }
    }
    header("Location: instructor.php?id=" . $uid);
    exit;
}

http_response_code(500);
echo 'Delete failed';
