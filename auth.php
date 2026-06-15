<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// إذا كان المستخدم مسجل دخول بالفعل، يتم توجيهه للرئيسية فوراً
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$error_message = "";

// 1️⃣ منطق معالجة البيانات عند إرسال الفورم (POST) باستخدام MySQLi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = getenv('DB_HOST') ?: 'localhost';
    $dbname = getenv('DB_DATABASE') ?: 'auratech_db';
    $username = getenv('DB_USERNAME') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';

    // الاتصال بقاعدة البيانات
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        error_log("Database Connection Error (MySQLi): " . $conn->connect_error);
        $error_message = "حدث خطأ في الاتصال بقاعدة البيانات.";
    } else {
        $conn->set_charset("utf8");

        $email = trim($_POST['email']);
        $password_input = trim($_POST['password']);

        if (!empty($email) && !empty($password_input)) {
            // استخدام Prepared Statements لحماية الموقع من الـ SQL Injection
            $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // التحقق من كلمة المرور (باستخدام الحماية القياسية المتوافقة مع تشفير الـ hash)
            if ($user && password_verify($password_input, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'] ?? 'user';

                header("Location: index.php");
                exit();
            } else {
                $error_message = "البريد الإلكتروني أو كلمة المرور غير صحيحة.";
            }
            $stmt->close();
        } else {
            $error_message = "يرجى ملء جميع الحقول المطلوبة.";
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | AuraTech Agency</title>
    <!-- خط Google Cairo المميز للواجهات الاحترافية -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- مكتبة FontAwesome للأيقونات العصرية -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-dark: #0f172a;        /* الخلفية الداكنة العميقة */
            --card-bg: #1e293b;        /* لون لوحة تسجيل الدخول */
            --accent-cyan: #06b6d4;    /* لون الـ Cyan المضيء للوكالة */
            --accent-blue: #3b82f6;    /* الأزرق التقني */
            --text-main: #f8fafc;      /* النص الأبيض الناصع */
            --text-muted: #94a3b8;     /* النص الرمادي الفرعي */
            --input-bg: #0f172a;       /* خلفية الحقول */
            --danger: #ef4444;         /* لون الخطأ الأحمر */
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            position: relative;
        }

        /* دوائر مضيئة متحركة لإعطاء لمسة الـ Figma الراقية في الخلفية */
        body::before, body::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--accent-cyan), var(--accent-blue));
            filter: blur(120px);
            opacity: 0.15;
            z-index: -1;
        }
        body::before { top: -50px; right: -50px; }
        body::after { bottom: -50px; left: -50px; }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 24px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .brand-logo {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(to left, var(--accent-cyan), var(--accent-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .brand-sub {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 35px;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: right;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
            padding-right: 4px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            right: 15px;
            color: var(--text-muted);
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 14px 45px 14px 15px;
            background-color: var(--input-bg);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-main);
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            text-align: right;
        }

        .form-control:focus {
            border-color: var(--accent-cyan);
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15);
        }

        .form-control:focus + i {
            color: var(--accent-cyan);
        }

        .error-alert {
            background-color: rgba(239, 68, 68, 0.1);
            border-right: 4px solid var(--danger);
            color: var(--danger);
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            text-align: right;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(90deg, var(--accent-blue), var(--accent-cyan));
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.3);
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
            opacity: 0.95;
        }

        .card-footer {
            margin-top: 30px;
            font-size: 13px;
            color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="brand-logo"><i class="fa-solid fa-laptop-code"></i> AuraTech Agency</div>
            <div class="brand-sub">لوحة إدارة المنتجات والإكسسوارات التقنية</div>

            <?php if (!empty($error_message)): ?>
                <div class="error-alert">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?php echo htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- يرسل البيانات لنفس الصفحة -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" autocomplete="off">
                
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    تسجيل الدخول <i class="fa-solid fa-arrow-left-to-bracket" style="margin-right: 8px;"></i>
                </button>
            </form>

            <div class="card-footer">
                نظام محمي ومتصل بقاعدة البيانات عبر بيئة العمل المؤتمتة بالكامل.
            </div>
        </div>
    </div>

</body>
</html>