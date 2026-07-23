<?php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // التحقق من صحة البيانات
    if (empty($name) || empty($email) || empty($password)) {
        $error = '❌ جميع الحقول مطلوبة';
    } elseif (strlen($password) < 6) {
        $error = '❌ كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    } elseif ($password !== $confirm_password) {
        $error = '❌ كلمة المرور غير متطابقة';
    } else {
        // التحقق من وجود البريد الإلكتروني
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = '❌ البريد الإلكتروني مسجل بالفعل';
        } else {
            // تشفير كلمة المرور
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";
            
            if (mysqli_query($conn, $query)) {
                $success = '✅ تم إنشاء الحساب بنجاح! يمكنك الآن <a href="index.php">تسجيل الدخول</a>';
            } else {
                $error = '❌ حدث خطأ أثناء إنشاء الحساب: ' . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - Healthy Life</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <i class="fas fa-user-plus"></i>
                <h1>إنشاء حساب</h1>
                <p>انضم إلينا في رحلة الصحة</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
            <form method="POST" action="">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="الاسم الكامل" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="البريد الإلكتروني" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="كلمة المرور (6 أحرف على الأقل)" required minlength="6">
                </div>
                <div class="input-group">
                    <i class="fas fa-check"></i>
                    <input type="password" name="confirm_password" placeholder="تأكيد كلمة المرور" required minlength="6">
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-user-plus"></i> إنشاء حساب
                </button>
            </form>
            <?php endif; ?>
            
            <div class="login-footer">
                <p>لديك حساب؟ <a href="index.php">تسجيل الدخول</a></p>
            </div>
        </div>
    </div>
</body>
</html>
