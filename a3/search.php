<?php
// search.php — Full-text search + category filter + pagination (prepared statements)

include 'includes/header.inc';
include 'includes/nav.inc';
include __DIR__ . '/includes/db_connect.inc';

// Force DB (consistent with rest of app)
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
function starts_with($h, $n)
{
    return substr($h, 0, strlen($n)) === $n;
}
function money_hr($v)
{
    return ($v === '' || $v === null) ? '' : ('$' . number_format((float)$v, 2) . '/hr');
}

// Inputs
$q        = isset($_GET['q'])   ? trim($_GET['q'])   : '';
$cat      = isset($_GET['cat']) ? trim($_GET['cat']) : 'all';
$page     = isset($_GET['p'])   && ctype_digit($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$per_page = 12;
$offset   = ($page - 1) * $per_page;

// Read category list for the filter dropdown
$cats = [];
if ($resCats = $conn->query("SELECT DISTINCT category FROM skills WHERE category IS NOT NULL AND category <> '' ORDER BY category")) {
    while ($r = $resCats->fetch_assoc()) $cats[] = $r['category'];
    $resCats->free();
}

// Detect FULLTEXT availability (we’ll prefer MATCH…AGAINST; otherwise fallback to LIKE)
$hasFulltext = false;
if ($idx = $conn->query("SHOW INDEX FROM skills WHERE Key_name = 'ft_skills'")) {
    $hasFulltext = ($idx->num_rows > 0);
    $idx->free();
}

// Build WHERE dynamically with prepared bindings
$where = [];
$types = '';
$args  = [];

// Category filter (exact match)
if ($cat !== '' && $cat !== 'all') {
    $where[] = "s.category = ?";
    $types  .= 's';
    $args[]  = $cat;
}

// Query: full-text or LIKE fallback
if ($q !== '') {
    if ($hasFulltext) {
        // Boolean mode lets users type: "guitar -beginner" etc.
        $where[] = "MATCH(s.title, s.description, s.category) AGAINST (? IN BOOLEAN MODE)";
        $types  .= 's';
        $args[]  = $q;
    } else {
        // Fallback: case-insensitive LIKE across key columns
        $where[] = "(s.title LIKE CONCAT('%', ?, '%') OR s.description LIKE CONCAT('%', ?, '%') OR s.category LIKE CONCAT('%', ?, '%'))";
        $types  .= 'sss';
        $args[]  = $q;
        $args[] = $q;
        $args[] = $q;
    }
}

// Assemble SQL
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

// Count query (for pagination)
$countSql = "
    SELECT COUNT(*) AS total
    FROM skills s
    $whereSql
";
$countStmt = $conn->prepare($countSql);
if ($types !== '') {
    $countStmt->bind_param($types, ...$args);
}
$countStmt->execute();
$total = 0;
if ($cr = $countStmt->get_result()) {
    $row = $cr->fetch_assoc();
    $total = (int)$row['total'];
}
$countStmt->close();

// Results query (join users for instructor username)
$sql = "
    SELECT
        s.skill_id, s.title, s.description, s.category, s.image_path,
        s.rate_per_hr, s.level, s.created_at, s.user_id,
        u.username AS instructor_name
    FROM skills s
    LEFT JOIN users u ON u.user_id = s.user_id
    $whereSql
    ORDER BY s.created_at DESC, s.skill_id DESC
    LIMIT ? OFFSET ?
";

// Bind with dynamic params + two integers for limit/offset
$stmt = $conn->prepare($sql);
if ($types !== '') {
    $bindTypes = $types . 'ii';
    $bindArgs  = array_merge($args, [$per_page, $offset]);
    $stmt->bind_param($bindTypes, ...$bindArgs);
} else {
    $stmt->bind_param('ii', $per_page, $offset);
}
$stmt->execute();
$res = $stmt->get_result();

// Prepare results
$results = [];
while ($row = $res->fetch_assoc()) $results[] = $row;
$stmt->close();

// Pagination helpers
$pages = max(1, (int)ceil($total / $per_page));
function page_link($p, $q, $cat)
{
    $params = ['p' => $p];
    if ($q   !== '') $params['q']   = $q;
    if ($cat !== '' && $cat !== 'all') $params['cat'] = $cat;
    return 'search.php?' . http_build_query($params);
}
?>
<main class="container my-5" style="max-width:1100px;">
    <h1 class="mb-2" style="color:#b84b1f;">Search skills</h1>
    <p class="text-muted mb-4">Use keywords and/or filter by category.</p>

    <!-- Search form -->
    <form class="row g-2 align-items-center mb-4" method="get" action="search.php">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control border-start-0" name="q"
                    value="<?php echo h($q); ?>" placeholder="Search skills (e.g., guitar +lesson -beginner)">
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" name="cat" onchange="this.form.submit()">
                <option value="all">All categories</option>
                <?php foreach ($cats as $c): ?>
                    <option value="<?php echo h($c); ?>" <?php if ($cat === $c) echo 'selected'; ?>>
                        <?php echo h($c); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-grid">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <!-- Result summary -->
    <div class="mb-3">
        <small class="text-muted">
            <?php if ($total === 0): ?>
                No results.
            <?php else: ?>
                Showing <?php echo count($results); ?> of <?php echo $total; ?> result<?php echo $total > 1 ? 's' : ''; ?>.
            <?php endif; ?>
        </small>
    </div>

    <!-- Results grid -->
    <?php if ($total > 0): ?>
        <div class="row g-4">
            <?php foreach ($results as $s):
                $img = trim((string)$s['image_path']);
                if ($img === '') {
                    $imgSrc = $IMG_DIR . 'placeholder.png';
                } else {
                    $imgSrc = (starts_with($img, 'http://') || starts_with($img, 'https://') || starts_with($img, '/'))
                        ? $img : $IMG_DIR . rawurlencode($img);
                }
            ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card border-0 shadow-sm h-100" style="border-radius:14px; overflow:hidden;">
                        <div style="aspect-ratio:16/11; overflow:hidden;">
                            <img src="<?php echo h($imgSrc); ?>" alt="<?php echo h($s['title']); ?>"
                                style="width:100%;height:100%;object-fit:cover;display:block;">
                        </div>
                        <div class="card-body">
                            <a href="details.php?id=<?php echo (int)$s['skill_id']; ?>"
                                class="stretched-link text-decoration-none" style="color:#b84b1f;">
                                <h5 class="card-title mb-1"><?php echo h($s['title']); ?></h5>
                            </a>
                            <div class="small text-muted mb-2">
                                Rate: <?php echo h(money_hr($s['rate_per_hr'])); ?>
                                <?php if (!empty($s['instructor_name'])): ?>
                                    · Instructor: @<?php echo h($s['instructor_name']); ?>
                                <?php endif; ?>
                            </div>
                            <div class="small text-muted">
                                <?php echo h($s['category']); ?> · <?php echo h($s['level']); ?>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($pages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination">
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo h(page_link(max(1, $page - 1), $q, $cat)); ?>">« Prev</a>
                    </li>
                    <?php
                    // compact range
                    $start = max(1, $page - 2);
                    $end   = min($pages, $page + 2);
                    for ($p = $start; $p <= $end; $p++): ?>
                        <li class="page-item <?php echo $p === $page ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo h(page_link($p, $q, $cat)); ?>"><?php echo $p; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $page >= $pages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo h(page_link(min($pages, $page + 1), $q, $cat)); ?>">Next »</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include 'includes/footer.inc'; ?>
<!-- Bootstrap JS (if not already loaded by header.inc/nav.inc) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>