<?php
/* ---------------------- footer.inc (self-contained) ---------------------- */
/* Optional debug (add ?footer_debug=1 to URL) */
$dbg = isset($_GET['footer_debug']);

/* Base URL (works on localhost & Titan) */
$APP_BASE = isset($_SERVER['SCRIPT_NAME']) ? rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') : '/';
if ($APP_BASE === '' || $APP_BASE === '\\' || $APP_BASE === '.') $APP_BASE = '/';

/* Ensure we have a LIVE mysqli $conn; if not, try to include it */
$hasLiveConn = (isset($conn) && is_object($conn) && method_exists($conn, 'ping') && @($conn->ping()));
if (!$hasLiveConn) {
    $dbInc = __DIR__ . '/db_connect.inc'; // footer.inc sits in /includes
    if (file_exists($dbInc)) {
        include $dbInc; // should define $conn
        $hasLiveConn = (isset($conn) && is_object($conn) && method_exists($conn, 'ping') && @($conn->ping()));
        if ($dbg && !$hasLiveConn) echo "<!-- footer: db_connect.inc included but no live mysqli connection -->";
    } else {
        if ($dbg) echo "<!-- footer: db_connect.inc not found at $dbInc -->";
    }
}

/* Fetch ALL registered users (ordered) */
$FOOTER_USERS = [];
if ($hasLiveConn) {
    try {
        $sql = "SELECT user_id, username FROM users ORDER BY username ASC";
        if ($stmt = @$conn->prepare($sql)) {
            if (@$stmt->execute()) {
                if (function_exists('mysqli_stmt_get_result')) {
                    if ($res = @$stmt->get_result()) {
                        while ($row = $res->fetch_assoc()) $FOOTER_USERS[] = $row;
                    }
                } else {
                    // Fallback for hosts without mysqlnd
                    @$stmt->store_result();
                    @$stmt->bind_result($uid, $uname);
                    while (@$stmt->fetch()) $FOOTER_USERS[] = ['user_id' => $uid, 'username' => $uname];
                }
            } else {
                if ($dbg) echo "<!-- footer: execute() failed: " . htmlspecialchars(@$stmt->error) . " -->";
            }
            @$stmt->close();
        } else {
            if ($dbg) echo "<!-- footer: prepare() failed: " . htmlspecialchars(@$conn->error) . " -->";
        }
    } catch (Throwable $e) {
        if ($dbg) echo "<!-- footer DB error: " . htmlspecialchars($e->getMessage()) . " -->";
    }
} else {
    if ($dbg) echo "<!-- footer: no live DB connection -->";
}
?>

<footer class="container-fluid" <?php if ($dbg) echo ' style="outline:3px solid red;position:relative;z-index:9999"'; ?>>
    <div class="container py-3">
        <div class="row align-items-center">
            <!-- LEFT: usernames list -->
            <div class="col-md-8 mb-2 mb-md-0">
                <?php if (!empty($FOOTER_USERS)): ?>
                    <div class="d-flex flex-wrap gap-2 small">
                        <?php foreach ($FOOTER_USERS as $u): ?>
                            <a class="text-white text-decoration-none"
                                href="<?php echo $APP_BASE; ?>/instructor.php?id=<?php echo (int)$u['user_id']; ?>">
                                @<?php echo htmlspecialchars($u['username']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <small class="opacity-75">No users yet.</small>
                <?php endif; ?>
            </div>

            <!-- RIGHT: copyright -->
            <div class="col-md-4 text-md-end text-center">
                <div class="small">&copy; 2025 SkillsSwap all rights reserved Tanath Medhanee</div>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts (keep) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $APP_BASE; ?>/assets/js/scripts.js"></script>