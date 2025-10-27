<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ue = trim($_POST['ue'] ?? '');
    $pw = $_POST['password'] ?? '';

    if ($ue === '' || $pw === '') {
        $errors[] = "Please enter your username/email and password.";
    } else {
        $row = null;
        $dbInc = __DIR__ . '/includes/db_connect.inc';
        if (file_exists($dbInc)) include $dbInc;

        if (isset($pdo) && $pdo instanceof PDO) {
            $stmt = $pdo->prepare("SELECT user_id, username, email, password FROM users WHERE username = :ue OR email = :ue LIMIT 1");
            $stmt->execute([':ue' => $ue]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } elseif (isset($mysqli) && $mysqli instanceof mysqli) {
            if ($stmt = $mysqli->prepare("SELECT user_id, username, email, password FROM users WHERE username = ? OR email = ? LIMIT 1")) {
                $stmt->bind_param("ss", $ue, $ue);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();
            }
        } else {
            $errors[] = "Database connection not found.";
        }

        if ($row && password_verify($pw, $row['password'])) {
            $_SESSION['user_id'] = (int)$row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            session_regenerate_id(true);
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid credentials.";
        }

        if ($row && password_verify($pw, $row['password'])) {
            $_SESSION['user_id'] = (int)$row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            session_regenerate_id(true);
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Invalid credentials.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include __DIR__ . '/includes/header.inc'; ?>

<body class="login-page">
    <?php include 'includes/nav.inc'; ?>

    <main class="auth-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <!-- wide center panel like your screenshot -->
                <div class="col-12 col-lg-10 col-xl-9">
                    <div class="auth-panel p-4 p-md-5 shadow-sm">
                        <h1 class="auth-title mb-4">Login</h1>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $e): ?>
                                    <div><?= htmlspecialchars($e) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" class="auth-form">
                            <div class="mb-3">
                                <label for="ue" class="form-label">Username or Email</label>
                                <input type="text" class="form-control form-control-lg" id="ue" name="ue" required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg px-4">Log in</button>
                        </form>

                        <p class="mt-3 mb-0">
                            Don’t have an account?
                            <a href="register.php" class="fw-semibold">Register</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.inc'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>