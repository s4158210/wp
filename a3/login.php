<?php
session_start();
require_once __DIR__ . '/includes/db_connect.inc'; // defines $conn (mysqli)

if (!isset($conn) || !$conn) {
    die('Database connection not available. Check includes/db_connect.inc.');
}

// If already logged in, bounce home
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

        $sql = "SELECT user_id, username, email, password
                FROM users
                WHERE username = ? OR email = ?
                LIMIT 1";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $ue, $ue);

            if (!mysqli_stmt_execute($stmt)) {
                $errors[] = "Database error: " . mysqli_stmt_error($stmt);
            } else {
                // works with or without mysqlnd
                if (function_exists('mysqli_stmt_get_result')) {
                    $result = mysqli_stmt_get_result($stmt);
                    $row = $result ? mysqli_fetch_assoc($result) : null;
                } else {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) === 1) {
                        mysqli_stmt_bind_result($stmt, $user_id, $username, $email, $password_hash);
                        mysqli_stmt_fetch($stmt);
                        $row = [
                            'user_id'  => $user_id,
                            'username' => $username,
                            'email'    => $email,
                            'password' => $password_hash
                        ];
                    }
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database prepare error: " . mysqli_error($conn);
        }

        if ($row && password_verify($pw, $row['password'])) {
            $_SESSION['user_id']  = (int)$row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email']    = $row['email'];
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

<head>
    <?php // use the SAME head as register.php so fonts/colors match
    include __DIR__ . '/includes/header.inc'; ?>
</head>

<!-- use the SAME body class as register.php so your styles apply -->

<body class="register-page">

    <?php include __DIR__ . '/includes/nav.inc'; ?>

    <!-- use the SAME structure/classes as register.php -->
    <main class="auth-section py-5">
        <div class="container">
            <div class="row justify-content-center">
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
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="ue"
                                    name="ue"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="password"
                                    name="password"
                                    required>
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

    <?php include __DIR__ . '/includes/footer.inc'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>