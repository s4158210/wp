<?php
session_start();
require_once __DIR__ . '/includes/db_connect.inc'; // defines $conn (procedural mysqli)

if (!isset($conn) || !$conn) {
    die('Database connection not available. Check includes/db_connect.inc.');
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $pw       = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';
    $bio      = trim($_POST['bio'] ?? '');

    // Basic validation
    if ($username === '' || $email === '' || $pw === '' || $confirm === '') {
        $errors[] = "Please fill in all required fields.";
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }
    if ($username !== '' && (strlen($username) < 3 || strlen($username) > 32)) {
        $errors[] = "Username must be 3–32 characters.";
    }
    if ($pw !== $confirm) {
        $errors[] = "Passwords do not match.";
    }
    if ($pw !== '' && strlen($pw) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (!$errors) {
        $hash = password_hash($pw, PASSWORD_DEFAULT);

        // --- Unique check (username OR email already exists) ---
        $sql_check = "SELECT 1 FROM users WHERE username = ? OR email = ? LIMIT 1";
        if ($stmt = mysqli_prepare($conn, $sql_check)) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = "Database error (checking uniqueness).";
            } else {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $errors[] = "Username or email already exists.";
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database prepare error: " . mysqli_error($conn);
        }

        // --- Insert new user ---
        if (!$errors) {
            $sql_insert = "INSERT INTO users (username, email, password, bio) VALUES (?, ?, ?, ?)";
            if ($stmt = mysqli_prepare($conn, $sql_insert)) {
                mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $hash, $bio);
                if (mysqli_stmt_execute($stmt)) {
                    $success = true;
                } else {
                    $errors[] = "Failed to create account: " . mysqli_stmt_error($stmt);
                }
                mysqli_stmt_close($stmt);
            } else {
                $errors[] = "Database prepare error (insert): " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . '/includes/header.inc'; ?>


<body class="register-page">
    <?php include 'includes/nav.inc'; ?>


    <main class="auth-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-9">
                    <div class="auth-panel p-4 p-md-5 shadow-sm">
                        <h1 class="auth-title mb-4">Create Account</h1>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $e): ?>
                                    <div><?= htmlspecialchars($e) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                Account created! You can now <a class="fw-semibold" href="login.php">log in</a>.
                            </div>
                        <?php endif; ?>

                        <form method="post" class="auth-form">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="username"
                                    name="username"
                                    minlength="3"
                                    maxlength="32"
                                    required
                                    value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input
                                    type="email"
                                    class="form-control form-control-lg"
                                    id="email"
                                    name="email"
                                    required
                                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="password"
                                    name="password"
                                    minlength="8"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm" class="form-label">Confirm Password</label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="confirm"
                                    name="confirm"
                                    minlength="8"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="bio" class="form-label">Bio (optional)</label>
                                <textarea
                                    class="form-control"
                                    id="bio"
                                    name="bio"
                                    rows="3"
                                    placeholder="Tell others a bit about your skills or interests..."><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg px-4">Create account</button>
                        </form>

                        <p class="mt-3 mb-0">
                            Already have an account? <a href="login.php" class="fw-semibold">Log in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.inc'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>