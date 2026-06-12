<?php
session_start();

/*
    Render PostgreSQL Connection (IMPORTANT)
    Uses DATABASE_URL instead of MySQL credentials
*/

try {
    $pdo = new PDO(getenv("DATABASE_URL"));
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Connection Failure: " . $e->getMessage());
}

$error = "";
$success = "";

/* =========================
   REGISTER SECTION
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    $user_name = trim($_POST['username']);
    $pass = $_POST['password'];

    if (empty($user_name) || empty($pass)) {
        $error = "All authorization fields are required.";
    } else {

        // Check if user exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$user_name]);

        if ($stmt->fetch()) {
            $error = "Username already exists.";
        } else {

            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users (username, password, role)
                VALUES (?, ?, 'admin')
            ");

            if ($stmt->execute([$user_name, $hashed_password])) {
                $success = "Account created successfully!";
            } else {
                $error = "Error while creating account.";
            }
        }
    }
}

/* =========================
   LOGIN SECTION
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $user_name = trim($_POST['username']);
    $pass = $_POST['password'];

    if (empty($user_name) || empty($pass)) {
        $error = "Please enter username and password.";
    } else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user_name]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: index.php");
            exit();

        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuraTech Agency - Portal Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --cyber-bg-top: #0b0f19;
            --cyber-bg-bottom: #1e1b4b;
            --neon-cyan: #06b6d4;
            --text-light: #f8fafc;
        }

        body {
            background: linear-gradient(180deg, var(--cyber-bg-top), var(--cyber-bg-bottom));
            color: var(--text-light);
            font-family: Arial;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-bar-layout {
            width: 100%;
            max-width: 900px;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-left: 5px solid var(--neon-cyan);
            border-radius: 14px;
            padding: 40px;
        }

        .brand-title {
            font-weight: 800;
            color: var(--neon-cyan);
        }

        .form-control {
            background: rgba(0,0,0,0.3);
            color: white;
        }

        .btn-auratech-submit {
            background: var(--neon-cyan);
            border: none;
            padding: 12px;
            font-weight: bold;
        }

        .hidden-section {
            display: none;
        }

        .alert {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

<div class="auth-bar-layout">

    <h2 class="brand-title text-center mb-4">AURATECH LOGIN</h2>

    <!-- ERROR / SUCCESS -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php endif; ?>

    <!-- LOGIN -->
    <div id="loginSection">

        <form method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button type="submit" name="login" class="btn btn-auratech-submit w-100">
                Login
            </button>
        </form>

        <p class="mt-3 text-center">
            No account?
            <a onclick="toggle()" style="color:#06b6d4; cursor:pointer;">Register</a>
        </p>
    </div>

    <!-- REGISTER -->
    <div id="registerSection" class="hidden-section">

        <form method="POST">
            <input type="text" name="username" class="form-control mb-3" placeholder="Username" required>
            <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

            <button type="submit" name="register" class="btn btn-auratech-submit w-100">
                Register
            </button>
        </form>

        <p class="mt-3 text-center">
            Already have account?
            <a onclick="toggle()" style="color:#06b6d4; cursor:pointer;">Login</a>
        </p>
    </div>

</div>

<script>
function toggle() {
    document.getElementById("loginSection").classList.toggle("hidden-section");
    document.getElementById("registerSection").classList.toggle("hidden-section");
}
</script>

</body>
</html>