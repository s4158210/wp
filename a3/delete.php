<?php
// delete.sphp — owner-only deletion with CSRF; removes DB row and image file
session_start();
include __DIR__ . '/includes/db_connect.inc';
if (method_exists($conn, 'select_db')) $conn->select_db('skillswap');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}
if (empty($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Not logged in');
}
if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
    http_response_code(400);
    exit('Bad CSRF');
}

$id = (isset($_POST['id']) && ctype_digit($_POST['id'])) ? (int)$_POST['id'] : 0;
if (!$id) {
    http_response_code(400);
    exit('Invalid id');
}

// Load skill (to verify owner and get image)
$st = $conn->prepare("SELECT user_id, image_path FROM skills WHERE skill_id = ? LIMIT 1");
$st->bind_param("i", $id);
$st->execute();
$skill = $st->get_result()->fetch_assoc();
$st->close();

if (!$skill) {
    http_response_code(404);
    exit('Not found');
}
if ((int)$skill['user_id'] !== (int)$_SESSION['user_id']) {
    http_response_code(403);
    exit('Forbidden');
}

// Delete DB row
$del = $conn->prepare("DELETE FROM skills WHERE skill_id = ? AND user_id = ? LIMIT 1");
$uid = (int)$_SESSION['user_id'];
$del->bind_param("ii", $id, $uid);
$ok = $del->execute();
$del->close();

if ($ok) {
    // Remove image file if exists
    if (!empty($skill['image_path'])) {
        $IMG_DIR_FS = __DIR__ . "/assets/images/skills/";
        $path = $IMG_DIR_FS . basename($skill['image_path']);
        if (is_file($path)) @unlink($path);
    }
    // Redirect somewhere sensible (back to instructor page)
    header("Location: instructor.php?id=" . $uid);
    exit;
}
http_response_code(500);
echo 'Delete failed';
