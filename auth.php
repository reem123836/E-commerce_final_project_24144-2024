<?php
// auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Integrated with your unified config environment
require_once 'config.php'; 

if (!isset($conn)) {
    die("Architectural Error: Database connection variable (\$conn) not found in config.");
}

// 0. Handle Logout Request (Terminate Workspace Session)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: auth.php");
    exit();
}

$error = "";
$success = "";

// 1. Handle Sign Up Request (Register Node)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $user_name = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (empty($user_name) || empty($pass)) {
        $error = "All authorization fields are required.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Identity node already exists. Choose another identity.";
            } else {
                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                $insert_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
                $insert_stmt->bind_param("ss", $user_name, $hashed_password);
                
                if ($insert_stmt->execute()) {
                    $success = "Identity initialized successfully! Proceed to system authorization.";
                } else {
                    $error = "Pipeline error during node injection.";
                }
                $insert_stmt->close();
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error = "Execution failure: " . $e->getMessage();
        }
    }
}

// 2. Handle Login Request (Authorize Access with Dynamic Routing)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_name = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if (empty($user_name) || empty($pass)) {
        $error = "Please provide valid access credentials.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $user_name);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($pass, $user['password'])) {
                // Session keys match global system expectations
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'] ?? 'admin';

                // Core adjustment: Dynamic workspace routing sequence
                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid credential parameters or access denied.";
            }
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $error = "Authentication failure: " . $e->getMessage();
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
            --neon-purple: #7c3aed;
            --neon-cyan: #06b6d4;
            --text-light: #f8fafc;
        }

        body {
            background: linear-gradient(180deg, var(--cyber-bg-top) 0%, var(--cyber-bg-bottom) 100%);
            color: var(--text-light);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            background-attachment: fixed;
        }

        .auth-bar-layout {
            width: 100%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-left: 5px solid var(--neon-cyan);
            border-radius: 14px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            transition: all 0.4s ease;
        }
        
        .auth-bar-layout:hover {
            border-color: rgba(6, 182, 212, 0.3);
            box-shadow: 0 20px 40px rgba(6, 182, 212, 0.15);
        }

        .brand-header {
            border-bottom: 1px solid rgba(6, 182, 212, 0.1);
            padding-bottom: 20px;
            margin-bottom: 35px;
        }

        .brand-title {
            font-weight: 800;
            letter-spacing: 1px;
            background: linear-gradient(90deg, #ffffff, var(--neon-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            margin: 0;
        }

        .form-label {
            color: var(--text-light);
            opacity: 0.8;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .form-control-auratech {
            background-color: rgba(11, 15, 25, 0.6) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border-radius: 8px !important;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control-auratech:focus {
            border-color: var(--neon-cyan) !important;
            box-shadow: 0 0 10px rgba(6, 182, 212, 0.4) !important;
            outline: none;
        }

        .btn-auratech-submit {
            background: linear-gradient(90deg, var(--neon-cyan), #0891b2);
            color: #0b0f19 !important;
            font-weight: 700;
            border: none;
            border-radius: 30px;
            padding: 14px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
        }

        .btn-auratech-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 35px rgba(6, 182, 212, 0.8);
        }

        .toggle-section-link, .home-link {
            color: var(--neon-cyan);
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .toggle-section-link:hover, .home-link:hover {
            color: #ffffff;
            text-shadow: 0 0 8px rgba(6, 182, 212, 0.6);
            text-decoration: underline;
        }

        .hidden-section {
            display: none !important;
        }

        .alert {
            border-radius: 8px;
            background-color: rgba(26, 15, 10, 0.4);
            border: 1px solid #4a2306;
            color: #ff9e59;
        }
        .alert-success {
            background-color: rgba(10, 26, 15, 0.4);
            border: 1px solid #064a1c;
            color: #59ff8b;
        }
    </style>
</head>
<body>

<div class="auth-bar-layout">
    <div class="brand-header d-flex justify-content-between align-items-center">
        <h1 class="brand-title">AURATECH AGENCY</h1>
        <span class="small opacity-50">INFRASTRUCTURE PORTAL</span>
    </div>
    
    <?php if (!empty($error)): ?>
        <div class="alert text-center mb-4"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center mb-4"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <div id="loginSection">
        <h3 class="mb-4" style="font-weight: 600; letter-spacing: 0.5px;">Sign In</h3>
        <form action="auth.php" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control form-control-auratech" required autocomplete="off">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-auratech" required>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" name="login" class="btn btn-auratech-submit w-100">Sign In</button>
                </div>
            </div>
        </form>
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="opacity-50">Need a new identity console?</span> 
                <span class="toggle-section-link ms-2" onclick="toggleAuthSections()">Create New Account</span>
            </div>
            <a href="index.php" class="home-link"><i class="bi bi-arrow-left me-1"></i> Back to Home</a>
        </div>
    </div>

    <div id="registerSection" class="hidden-section">
        <h3 class="mb-4" style="font-weight: 600; letter-spacing: 0.5px;">Initialize New Identity</h3>
        <form action="auth.php" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control form-control-auratech" required autocomplete="off">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Secure Password</label>
                    <input type="password" name="password" class="form-control form-control-auratech" required>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" name="register" class="btn btn-auratech-submit w-100">Register Pipeline Node</button>
                </div>
            </div>
        </form>
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <span class="opacity-50">Already provisioned inside the nodes?</span> 
                <span class="toggle-section-link ms-2" onclick="toggleAuthSections()">Return to Login</span>
            </div>
            <a href="index.php" class="home-link"><i class="bi bi-arrow-left me-1"></i> Back to Home</a>
        </div>
    </div>
</div>

<script>
    function toggleAuthSections() {
        var loginSection = document.getElementById('loginSection');
        var registerSection = document.getElementById('registerSection');
        
        if (loginSection.classList.contains('hidden-section')) {
            loginSection.classList.remove('hidden-section');
            registerSection.classList.add('hidden-section');
        } else {
            loginSection.classList.add('hidden-section');
            registerSection.classList.remove('hidden-section');
        }
    }
</script>

</body>
</html>