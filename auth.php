<?php
session_start();

// Database connection settings for AuraTech Container environment
$host = 'db'; 
$dbname = 'laptop_agency_db';
$username = 'root';
$password = 'root_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failure: " . $e->getMessage());
}

$error = "";
$success = "";

// 1. Handle Sign Up Request (Register Node)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $user_name = trim($_POST['username']);
    $pass = $_POST['password'];

    if (empty($user_name) || empty($pass)) {
        $error = "All authorization fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$user_name]);
        if ($stmt->fetch()) {
            $error = "Identity node already exists. Choose another identity.";
        } else {
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
            if ($stmt->execute([$user_name, $hashed_password])) {
                $success = "Identity initialized successfully! Proceed to system authorization.";
            } else {
                $error = "Pipeline error during node injection.";
            }
        }
    }
}

// 2. Handle Login Request (Authorize Access)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user_name = trim($_POST['username']);
    $pass = $_POST['password'];

    if (empty($user_name) || empty($pass)) {
        $error = "Please provide valid access credentials.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$user_name]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid credential parameters or access denied.";
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

        /* Long Rectangle layout integrated with glass-morphism and cyber metrics */
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

        /* Luminous submit actions designed explicitly for AuraTech */
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

        .toggle-section-link {
            color: var(--neon-cyan);
            text-decoration: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .toggle-section-link:hover {
            color: #ffffff;
            text-shadow: 0 0 8px rgba(6, 182, 212, 0.6);
            text-decoration: underline;
        }

        .hidden-section {
            display: none !important;
        }

        /* Cybernetic system status monitors */
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
        <div class="alert text-center mb-4"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center mb-4"><?php echo $success; ?></div>
    <?php endif; ?>

    <div id="loginSection">
        <h3 class="mb-4" style="font-weight: 600; letter-spacing: 0.5px;">Account Verification</h3>
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
                    <button type="submit" name="login" class="btn btn-auratech-submit w-100">Authorize System Access</button>
                </div>
            </div>
        </form>
        <div class="mt-4 text-center">
            <span class="opacity-50">Need a new identity console?</span> 
            <span class="toggle-section-link ms-2" onclick="toggleAuthSections()">Create Admin Account</span>
        </div>
    </div>

    <div id="registerSection" class="hidden-section">
        <h3 class="mb-4" style="font-weight: 600; letter-spacing: 0.5px;">Initialize New Identity</h3>
        <form action="auth.php" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Preferred Username</label>
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
        <div class="mt-4 text-center">
            <span class="opacity-50">Already provisioned inside the nodes?</span> 
            <span class="toggle-section-link ms-2" onclick="toggleAuthSections()">Return to Login</span>
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