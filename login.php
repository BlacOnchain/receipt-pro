<?php
session_start();
include 'db_config.php';

if (isset($_POST['login'])) {
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    // Now we check the 'settings' table for the password you saved
    $result = mysqli_query($conn, "SELECT * FROM settings WHERE admin_password='$pass' LIMIT 1");
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid Admin Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Receipt Pro</title>
    <style>
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #f0f2f5; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .login-card { 
            background: white; 
            padding: 40px; 
            border-radius: 15px; 
            width: 350px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            text-align: center;
        }
        .logo-container img {
            height: 70px;
            width: auto;
            margin-bottom: 15px;
        }
        input { 
            width: 100%; 
            padding: 12px; 
            margin: 10px 0; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            box-sizing: border-box;
            font-size: 16px;
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background: #007bff; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .error-msg {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-container">
            <img src="logo.png.jfif" alt="Logo">
        </div>

        <h2 style="margin: 0 0 20px 0; color: #333;">Admin Login</h2>

        <?php if(isset($error)): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="password" name="password" placeholder="Enter Admin Password" required>
            <button type="submit" name="login">Access Dashboard</button>
        </form>
        
        <p style="margin-top: 25px; font-size: 11px; color: #aaa; text-transform: uppercase; letter-spacing: 1px;">
            Receipt Pro Security
        </p>
    </div>

</body>
</html>