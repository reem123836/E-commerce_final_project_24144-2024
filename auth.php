<?php
// 1. بدء جلسة العمل وتفعيل نظام تقارير الأخطاء
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 2. إعدادات الاتصال بقاعدة البيانات الخاصة بالدوكر (تعديل الـ DSN المصحح)
$db_host = 'db'; 
$db_name = 'laptop_agency_db';
$db_user = 'root';
$db_pass = 'root';

try {
    // السطر المصحح الذي يقضي على خطأ Argument #1 ($dsn)
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
    $conn = new PDO($dsn, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8mb4");
} catch (PDOException $e) {
    die("Database Connection Failure: " . $e->getMessage());
}

// 3. منطق معالجة البيانات عند إرسال النموذج (Form Submission)
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error_message = 'الرجاء إدخال اسم المستخدم وكلمة المرور كاملاً.';
    } else {
        try {
            // استعلام التحقق من المستخدم
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // التحقق من كلمة المرور (يدعم التشفير أو المطابقة المباشرة حسب نظام مشروعك)
            if ($user && ($password === $user['password'] || password_verify($password, $user['password']))) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'] ?? 'admin'; // افتراضي مدير النظام
                
                // التوجيه لصفحة المنتجات بعد النجاح
                header("Location: products.php");
                exit;
            } else {
                $error_message = 'اسم المستخدم أو كلمة المرور غير صحيحة!';
            }
        } catch (PDOException $e) {
            $error_message = 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage();
        }
    }
}

// منطق تسجيل الخروج المدمج (Logout Action)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: auth.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة التحكم الآمنة | Hardware Ecosystem</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --card-bg: rgba(30, 41, 59, 0.7);
            --neon-blue: #0ea5e9;
            --neon-cyan: #22d3ee;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-glow: rgba(14, 165, 233, 0.2);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            position: relative;
        }

        /* تأثيرات النيون في الخلفية لتعزيز واجهة الـ Ecosystem */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: var(--neon-blue);
            filter: blur(120px);
            opacity: 0.15;
            z-index: 0;
        }

        body::before { top: 10%; right: 10%; }
        body::after { bottom: 10%; left: 10%; }
     .auth-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .auth-card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--border-glow);
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auth-card:hover {
            box-shadow: 0 25px 50px rgba(14, 165, 233, 0.15), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        .brand-header {
            margin-bottom: 35px;
        }

        .brand-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, var(--neon-blue), var(--neon-cyan));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            display: inline-block;
        }

        .brand-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            background: linear-gradient(to left, #ffffff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .brand-header p {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 22px;
            text-align: right;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #cbd5e1;
            padding-right: 4px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 14px 45px 14px 15px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--text-main);
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--neon-blue);
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 12px rgba(14, 165, 233, 0.3);
        }

        .form-control:focus + i {
            color: var(--neon-blue);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-align: right;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--neon-blue) 0%, #0284c7 100%);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
           .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.4);
            background: linear-gradient(135deg, var(--neon-cyan) 0%, var(--neon-blue) 100%);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .footer-credits {
            margin-top: 30px;
            font-size: 0.75rem;
            color: rgba(148, 163, 184, 0.5);
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <div class="brand-header">
            <div class="brand-icon">
                <i class="fa-solid to-cube"></i> <i class="fa-solid var(--neon-blue) fa-server"></i>
            </div>
            <h1>Hardware Ecosystem</h1>
            <p>بوابة تسجيل الدخول الآمنة للنظام الإداري</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert-danger">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?php echo htmlspecialchars($error_message); ?></span>
            </div>
        <?php endif; ?>

        <form action="auth.php" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" class="form-control" placeholder="أدخل اسم المستخدم المعتمد" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>

<div class="form-group">
                <label for="password">كلمة المرور المشفرة</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>تخطي المصادقة والدخول</span>
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>

        <div class="footer-credits">
            <p>Designed by Reem Osama &copy; 2026</p>
        </div>
    </div>
</div>

</body>
</html>