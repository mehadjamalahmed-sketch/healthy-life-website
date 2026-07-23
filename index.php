<?php
require_once 'config.php';

// إذا كان المستخدم مسجل الدخول بالفعل، انتقل إلى لوحة التحكم
if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    // البحث عن المستخدم في قاعدة البيانات
    $query = "SELECT id, name, email, password FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // التحقق من كلمة المرور (مشفرة)
        if (password_verify($password, $user['password'])) {
            // تخزين بيانات المستخدم في الجلسة
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            redirect('dashboard.php');
        } else {
            $error = '❌ كلمة المرور غير صحيحة';
        }
    } else {
        $error = '❌ البريد الإلكتروني غير مسجل';
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - Healthy Life</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-heartbeat"></i>
                <h1>Healthy Life</h1>
                <p>نحو حياة أكثر صحة ونشاط</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="كلمة المرور" required>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-sign-in-alt"></i> تسجيل الدخول
                </button>
            </form>
            
            <div class="login-footer">
                <p>ليس لديك حساب؟ <a href="register.php">سجل الآن</a></p>
            </div>
        </div>
    </div>
</body>
</html>
