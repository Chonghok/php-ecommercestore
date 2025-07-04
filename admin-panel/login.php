<?php
session_start();
require_once __DIR__ . '/admin-includes/config.php';
require_once __DIR__ . '/admin-includes/auth.php';

// Check if user is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
$username_value = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $username_value = htmlspecialchars($username);
    
    $authResult = authenticateAdmin($username, $password, $conn);
    
    if ($authResult['success']) {
        // Login successful
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $authResult['admin']['id'];
        $_SESSION['admin_username'] = $authResult['admin']['username'];
        $_SESSION['admin_email'] = $authResult['admin']['email'];
        
        header('Location: index.php');
        exit;
    } else {
        $error = $authResult['error'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In - LetsGear Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin-assets/css/login.css">
</head>
<body>
    <main>
        <div class="login-wrapper">
            <div class="login-box">
                <h2>LetsGear Admin Login</h2>
                <?php if (!empty($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="input-group">
                        <label for="username">Username or Email</label>
                        <input type="text" id="username" name="username" 
                            placeholder="Enter your username or email" 
                            value="<?php echo $username_value; ?>" 
                            required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" 
                            placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn-login">Log In</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
